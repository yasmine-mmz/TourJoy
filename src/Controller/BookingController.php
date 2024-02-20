<?php

namespace App\Controller;

use App\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookingRepository;
use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Component\HttpFoundation\Request;
use App\Form\BookType;
use App\Entity\Guide;
use App\Repository\GuideRepository;
use Doctrine\ORM\EntityManagerInterface;





class BookingController extends AbstractController
{
    #[Route('/booking', name: 'app_booking')]
    public function index(): Response
    {
        return $this->render('booking/index.html.twig', [
            'controller_name' => 'BookingController',
        ]);
    }

    #[Route('/fetchb/{id}', name: 'fetchb')]
    public function fetchb(int $id, BookingRepository $repo, GuideRepository $guideRepository): Response
    {
        $guide = $guideRepository->find($id);
    
        if (!$guide) {
            throw $this->createNotFoundException('Guide not found');
        }
    
        // Fetch bookings for the selected guide
        $bookings = $repo->findBy(['guide_id' => $guide]);
    
        return $this->render('booking/show.html.twig', [
            'bookings' => $bookings,
        ]);
    }
    
    #[Route('/addB{guide_cin}', name: 'addB')] 
    public function addB(Request $request, string $guide_cin, ManagerRegistry $managerRegistry): Response
    {
        $guide = $this->getDoctrine()->getRepository(Guide::class)->findOneBy(['CIN' => $guide_cin]);
        if (!$guide) {
            throw $this->createNotFoundException('Guide not found');
        }
        $booking = new Booking();
        $booking->setGuideId($guide); 
        $form = $this->createForm(BookType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();
            
         return $this->redirectToRoute('booking_success');

        }
        return $this->render('booking/add.html.twig', [
            'guide' => $guide,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/booking-success', name: 'booking_success')]
public function bookingSuccess(): Response
{
    return $this->render('booking/success.html.twig');
}
    #[Route('/deleteb/{id}', name: 'deleteb')]
    public function deleteb(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $bookingRepository = $this->getDoctrine()->getRepository(Booking::class);
        $booking = $bookingRepository->find($id);

        if (!$booking) {
            throw $this->createNotFoundException('Booking not found');
        }

        $entityManager->remove($booking);
        $entityManager->flush();

        // Redirect back to the page where bookings are displayed
        return $this->redirectToRoute('fetchb');
        
    }

 
    
}
