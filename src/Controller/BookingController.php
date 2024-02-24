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
use Symfony\Component\HttpFoundation\RedirectResponse;





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
 
#[Route('/calendarData/{year?}/{month?}', name: 'calendar_data')]
public function calendarData(Request $request, ?int $year = null, ?int $month = null): Response
{
    $year = $year ?? date('Y');
    $month = $month ?? date('m');

    // Get tomorrow's date
    $tomorrow = new \DateTime('tomorrow');

    // Set the initial date to the first day of the next month starting from tomorrow
    $firstDayOfMonth = new \DateTime($tomorrow->format('Y-m-01'));

    // Calculate the last day of the month
    $lastDayOfMonth = new \DateTime($firstDayOfMonth->format('Y-m-t'));
    
    $days = [];
    
    // Fill the array with days of the month
    for ($day = clone $firstDayOfMonth; $day <= $lastDayOfMonth; $day->modify('+1 day')) {
        $days[] = [
            'date' => $day->format('Y-m-d'),
            'day' => $day->format('j'),
            'isBooked' => false, // Implement logic to determine if the day is booked
        ];
    }

    return $this->json([
        'days' => $days,
        'startDay' => (int)$firstDayOfMonth->format('N') - 1,
        'totalDays' => count($days),
    ]);
}


#[Route('/addB{guide_cin}', name: 'addB')]
public function addB(Request $request, string $guide_cin, ManagerRegistry $managerRegistry, BookingRepository $bookingRepository): Response
{    
    $guide = $this->getDoctrine()->getRepository(Guide::class)->findOneBy(['CIN' => $guide_cin]);
    if (!$guide) {
        throw $this->createNotFoundException('Guide not found');
    }

    // Fetch all booking dates for the guide
    $bookings = $bookingRepository->findBy(['guide_id' => $guide]);
    $bookedDates = array_map(function ($booking) {
        return $booking->getDate()->format('Y-m-d');
    }, $bookings);

    $booking = new Booking();
    $booking->setGuideId($guide); // Make sure the method matches your Guide entity

    $form = $this->createForm(BookType::class, $booking);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Retrieve the selected date from the form
        $selectedDate = $form->get('selectedDate')->getData();
        $booking->setDate(new \DateTime($selectedDate));

        $entityManager = $managerRegistry->getManager();
        $entityManager->persist($booking);
        $entityManager->flush();

        return $this->redirectToRoute('booking_success');
    }

    return $this->render('booking/add.html.twig', [
        'guide' => $guide,
        'form' => $form->createView(),
        'bookedDates' => json_encode($bookedDates),
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
