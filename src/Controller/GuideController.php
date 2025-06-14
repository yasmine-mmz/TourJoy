<?php

namespace App\Controller;

use App\Entity\Guide;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GuideRepository;
use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Component\HttpFoundation\Request;
use App\Form\GuideType;
use Knp\Component\Pager\PaginatorInterface; // Make sure this is included
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;



class GuideController extends AbstractController
{
    #[Route('/guide', name: 'app_guide')]
    public function index(): Response
    {
        return $this->render('guide/index.html.twig', [
            'controller_name' => 'GuideController',
        ]);
    }

    #[Route('/fetchg', name: 'fetchg')]
    public function fetchg(Request $request, GuideRepository $repo, PaginatorInterface $paginator): Response // Inject PaginatorInterface here
    {
        $genders = $request->query->get('gender', []);
        $ratings = $request->query->get('rating', []);
        $sortByAge = $request->query->get('sortByAge');

        $results = $repo->findGuidesFiltered($genders, $ratings, $sortByAge);
        $guides = array_map(function ($result) {
            return $result[0];
        }, $results);

        // Use $paginator here correctly, as it's now defined via method injection
        $pagination = $paginator->paginate(
            $guides, // query NOT result
            $request->query->getInt('page', 1), // Current page number, default to 1
            5 // Number of items per page
        );
        return $this->render('guide/show.html.twig', [
            'genders' => $genders,
            'selectedRatings' => $ratings,
            'sortByAge' => $sortByAge, // Pass the sortByAge parameter to the template
            'pagination' => $pagination,

        ]);

        
    }
    
   

    #[Route('/fetch2', name: 'fetch2')] 
    public function fetch2(GuideRepository $repo): Response
    {
        $result = $repo->findAll();

        return $this->render('guide/show2.html.twig', [
            'guides' => $result,
        ]);
    }
 
    #[Route('/addFg', name: 'addFg')] 
    public function addF(ManagerRegistry $mr,Request $req): Response
    {
       
        $g = new Guide();//-1instance
        $form=$this->createform(GuideType::class,$g);///2-
        $form->handleRequest($req);
        if($form->isSubmitted()&& $form->isValid()) {
            $em = $mr->getManager(); ///3- persist flush
            $em->persist($g);
            $em->flush();
            return $this ->redirectToRoute('fetchg');    
        }
        
        return $this-> render('guide/add.html.twig',[
            'f'=>$form->createView()
        ]);
    }



    #[Route('/updateg{id}', name: 'updateg')]
    public function updateGuide(int $id, ManagerRegistry $mr, Request $req, GuideRepository $repo): Response
    {
        $s = $repo->find($id); // Find the student to update
    
        if (!$s) {
            throw $this->createNotFoundException('Guide not found.');
        }
        $form = $this->createForm(GuideType::class, $s); 
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
           
            $em->flush();
            return $this->redirectToRoute('fetchg'); 
        }
        return $this->render('guide/add.html.twig', [
            'f' => $form->createView()
        ]);
    }
    
    #[Route('/removeg/{id}', name: 'removeg')]
    public function remove(GuideRepository $repo, $id, ManagerRegistry $mr, MailerInterface $mailer, \Twig\Environment $twig): Response
    {
        $guide = $repo->find($id);
        if (!$guide) {
            throw $this->createNotFoundException('Guide not found');
        }
    
        $em = $mr->getManager();
        foreach ($guide->getFeedback() as $feedback) {
            // Remove related Likesystem entities before removing the Feedback
            foreach ($feedback->getLikesystems() as $likesystem) {
                $em->remove($likesystem);
            }
            $em->remove($feedback);
        }
    
        foreach ($guide->getBookings() as $booking) {
            $user = $booking->getUser();
            if ($user) {
                $email = (new TemplatedEmail())
                    ->from('no-reply@tourjoy.com')
                    ->to($user->getEmail())
                    ->subject('Booking Cancellation')
                    ->htmlTemplate('guide/booking_cancellation.html.twig')
                    ->context([
                        'user' => $user,
                        'guide' => $guide,
                        'booking' => $booking, // Pass the booking variable to the template

                    ]);
                $mailer->send($email);
            }
            $em->remove($booking);
        }
    
        $em->remove($guide);
        $em->flush();
    
        return $this->redirectToRoute('fetchg');
    }
    


}
