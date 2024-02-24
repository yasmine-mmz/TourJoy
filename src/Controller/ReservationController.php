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
use App\Form\ReservationType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        return $this->render('BackOffice/res.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
    #[Route('/fetch', name: 'fetch')] 
    public function show(ReservationRepository $repo): Response
    {
        $result = $repo->findAll();

        return $this->render('BackOffice/res.html.twig', [
            'response' => $result
        ]);
    }

    #[Route('/addF', name: 'addF')]
    public function addF(ManagerRegistry $mr, Request $req, TranslatorInterface $translator): Response
    {
        $p = new Reservation();
        $form = $this->createForm(ReservationType::class, $p);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($p);
            $em->flush();
            $this->addFlash('success', $translator->trans('Contact.Form.SuccessMsg'));
            return $this->redirectToRoute('thank_you');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            throw new Exception('Invalid form');
        }

        return $this->render('FrontOffice/resForm.html.twig', [
            'f' => $form->createView()
        ]);
    }

    

    #[Route('/thank-you', name: 'thank_you')]
    public function thankYou(): Response
    {
        return $this->render('FrontOffice/thank_you.html.twig');
    }
    


   

    #[Route('/remove/{idR}', name: 'remove')]
    public function remove(ReservationRepository $repo, $idR, ManagerRegistry $mr):Response
    {
        $reservation = $repo->find($idR);
        $em = $mr->getManager();
        $em->remove($reservation);
        $em->flush(); 

        return $this ->redirectToRoute('fetch');
    }
    #[Route('/update{idR}', name: 'update')]
    public function updateReservation(int $idR, ManagerRegistry $mr, Request $req, ReservationRepository $repo): Response
    {
        $p = $repo->find($idR); 
    
        if (!$p) {
            throw $this->createNotFoundException('Reservation not found.');
        }
    
        $form = $this->createForm(ReservationType::class, $p); 
    
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->flush();
    
            return $this->redirectToRoute('fetch');
        }
    
        return $this->render('BackOffice/ResUpd.html.twig', [
            'f' => $form->createView()
        ]);
    }



    
    
}
