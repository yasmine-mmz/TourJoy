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
use Symfony\Component\Security\Core\Security;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;




class BookingController extends AbstractController
{
    #[Route('/booking', name: 'app_booking')]
    public function index(): Response
    {
        return $this->render('booking/index.html.twig', [
            'controller_name' => 'BookingController',
        ]);
    }

    #[Route('/fetchb{id}', name: 'fetchb')]
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
public function addB(Request $request, string $guide_cin, ManagerRegistry $managerRegistry, BookingRepository $bookingRepository, Security $security): Response
{    
    $entityManager = $managerRegistry->getManager();
    
    // Fetch the guide using the provided CIN
    $guide = $entityManager->getRepository(Guide::class)->findOneBy(['CIN' => $guide_cin]);
    if (!$guide) {
        throw $this->createNotFoundException('Guide not found');
    }

    // Fetch all booking dates for the guide
    $bookings = $bookingRepository->findBy(['guide_id' => $guide]);
    $bookedDates = array_map(function ($booking) {
        return $booking->getDate()->format('Y-m-d');
    }, $bookings);

    // Create a new booking instance
    $booking = new Booking();
    $booking->setGuideId($guide); // Associate the booking with the guide

    // Get the currently logged-in user
    $user = $security->getUser();
    if (!$user) {
        // Optionally handle the case where there's no authenticated user
        throw $this->createAccessDeniedException('You must be logged in to submit feedback.');
    }
    // Set the logged-in user to the booking
    $booking->setUser($user);

    // Create and handle the booking form
    $form = $this->createForm(BookType::class, $booking);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Persist the booking entity to the database
        $entityManager->persist($booking);
        $entityManager->flush();

        // Redirect after successful booking
        return $this->redirectToRoute('booking_success');
    }

    // Render the booking form template
    return $this->render('booking/add.html.twig', [
        'guide' => $guide,
        'form' => $form->createView(),
        'bookedDates' => json_encode($bookedDates),
        'selectedDate' => $booking->getDate() ? $booking->getDate()->format('Y-m-d') : null,
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

#[Route('/back', name: 'bookings_per_guide')]
public function bookingStats(BookingRepository $bookingRepository): Response
{
    $bookingStats = $bookingRepository->countBookingsByGuide();

    $labels = [];
    $data = [];
    foreach ($bookingStats as $stat) {
        $labels[] = $stat['guideName']; // Adjust based on actual returned array key
        $data[] = $stat['bookingCount']; // Adjust based on actual returned array key
    }

    return $this->render('BackOffice/back_template.html.twig', [
        'labels' => json_encode($labels),
        'data' => json_encode($data),
    ]);
}

}
