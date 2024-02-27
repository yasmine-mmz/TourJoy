<?php

namespace App\Controller;

use App\Entity\Feedback;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FeedbackRepository;
use App\Repository\GuideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Component\HttpFoundation\Request;
use App\Form\FeedbackType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;



class FeedbackController extends AbstractController
{
    #[Route('/feedback', name: 'app_feedback')]
    public function index(): Response
    {
        return $this->render('feedback/index.html.twig', [
            'controller_name' => 'FeedbackController',
        ]);
    }
    
    

    #[Route('/addfb', name: 'addfb')]
    public function addAndFetchFeedbacks(FeedbackRepository $repo, Request $req, Security $security): Response
    {
        $feedback = new Feedback();
        $user = $security->getUser(); // Get the currently authenticated user
    
        if (!$user) {
            // Optionally handle the case where there's no authenticated user
            throw $this->createAccessDeniedException('You must be logged in to submit feedback.');
        }
    
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $feedback->setUserId($user); // Associate the authenticated user with the feedback
            $em->persist($feedback);
            $em->flush();
    
            return $this->redirectToRoute('addfb'); // Redirect to prevent resubmission
        }
    
        $feedbacks = $repo->findAll();
    
      
    
        return $this->render('feedback/add.html.twig', [
            'feedbacks' => $feedbacks,
            'form' => $form->createView(),
        ]);
    }
    



    #[Route('/fetchfg{id}', name: 'fetchfg')]
    public function fetchfg(int $id, FeedbackRepository $repo, GuideRepository $guideRepository): Response
    {
        $guide = $guideRepository->find($id);
    
        if (!$guide) {
            throw $this->createNotFoundException('Guide not found');
        }
    
        // Fetch feedbacks for the selected guide
        $feedbacks = $repo->findBy(['fkGuide' => $guide]);
    
        return $this->render('feedback/show.html.twig', [
            'feedbacks' => $feedbacks,
        ]);
    }


    private Security $security; // Declare the $security property here

    public function __construct(Security $security)
    {
        $this->security = $security; // Initialize $security in the constructor
    }


   
}
