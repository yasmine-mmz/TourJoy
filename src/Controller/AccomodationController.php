<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Accomodation;
use App\Repository\AccomodationRepository;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Reservation;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AccomodationType;
use App\Form\ReservationType;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AccomodationController extends AbstractController
{
    #[Route('/accomodation', name: 'app_accomodation')]
    public function index(): Response
    {
        return $this->render('BackOffice/acc.html.twig', [
            'controller_name' => 'AccomodationController',
        ]);
    }
    #[Route('/show', name: 'show')] 
    public function show(AccomodationRepository $repo): Response
    {
        $result = $repo->findAll();

        return $this->render('BackOffice/acc.html.twig', [
            'response' => $result
        ]);
    }
    #[Route('/showF', name: 'showF')] 
    public function showF(AccomodationRepository $repo): Response
    {
        $result = $repo->findAll();

        return $this->render('FrontOffice/acc.html.twig', [
            'response' => $result
        ]);
    }




    #[Route('/addF1', name: 'addF1')] 
    public function addF1(ManagerRegistry $mr, Request $req, ValidatorInterface $validator): Response
    {
        $accomodation = new Accomodation();
        $form = $this->createForm(AccomodationType::class, $accomodation);
    
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($accomodation);
    
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                return new Response($errorsString);
            }
    
            $em = $mr->getManager();
            $em->persist($accomodation);
            $em->flush();
    
            return $this->redirectToRoute('show');
             return $this->redirectToRoute('showF');
        }
    
        return $this->render('BackOffice/AccForm.html.twig', [
            'f' => $form->createView()
        ]);
    }
    

    #[Route('/remove1/{refA}', name: 'remove1')]
    public function remove1(AccomodationRepository $repo, $refA, ManagerRegistry $mr):Response
    {
        $accomodation = $repo->find($refA);
        $em = $mr->getManager();
        foreach ($accomodation->getReservations() as $reservation)
        {
            $em->remove($reservation);
        }
        $em->remove($accomodation);
        $em->flush(); 

        return $this ->redirectToRoute('show');
        return $this ->redirectToRoute('showF');    

    }
    #[Route('/update1{refA}', name: 'update1')]
    public function updateAccomodation(int $refA, ManagerRegistry $mr, Request $req, AccomodationRepository $repo): Response
    {
        $p = $repo->find($refA); 
    
        if (!$p) {
            throw $this->createNotFoundException('Accomodation not found.');
        }
    
        $form = $this->createForm(AccomodationType::class, $p); 
    
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->flush();
    
            return $this->redirectToRoute('show');
            return $this ->redirectToRoute('showF');    

        }
    
        return $this->render('BackOffice/AccForm.html.twig', [
            'f' => $form->createView()
        ]);
    }

    #[Route('/book-accommodation{refA}', name: 'book_accommodation')]
    public function bookAccommodation(int $refA, Request $request, ManagerRegistry $mr): Response
    {
    $name = $this->getDoctrine()->getRepository(Accomodation::class)->find($refA);

    if (!$name) {
        throw $this->createNotFoundException('Accommodation not found');
    }

    $reservation = new Reservation();
    $reservation->setName($name);

    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $mr->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->redirectToRoute('thank_you');
    }

    return $this->render('FrontOffice/ResForm.html.twig', [
        'f' => $form->createView(),
    ]);
}

}
