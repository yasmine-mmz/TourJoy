<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Transport;
use App\Form\TransportType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use App\Repository\BookingRepository;
use App\Repository\AccomodationRepository;
use App\Repository\ClaimsRepository;
use App\Repository\CountryRepository;
use App\Repository\GuideRepository;
use App\Repository\MonumentRepository;
use App\Repository\ReservationRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\TransportRepository;

class TransportController extends AbstractController
{
    #[Route('/transport', name: 'app_transport')]
    public function index(): Response
    {
        return $this->render('transport/index.html.twig', [
            'controller_name' => 'TransportController',
        ]);
    }
    #[Route('/showT', name: 'showT')]
    public function show(TransportRepository $rep): Response
    {
        $transportList = $rep->findAll();
        return $this->render('transport/index.html.twig', ['transportList'=>$transportList]);
    }
    #[Route('/createT', name: 'createT')]
    public function createT(ManagerRegistry $doctrine,Request $request): Response
    {
        $transport = new Transport();
        $form = $this->createForm(TransportType::class, $transport);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();
            $em->persist($transport);
            $em->flush();
            return $this-> redirectToRoute('showT');
        }
        return $this->render('transport/createT.html.twig',[
            'form' => $form->createView()
        ]);
    }
    #[Route('/updateT{id}', name: 'updateT')]
    public function UpdateT(ManagerRegistry $doctrine, Request $request, TransportRepository $rep, $id): Response
    {
       $transport = $rep->find($id);
       $form=$this->createForm(TransportType::class,$transport);
       $form->handleRequest($request);
       if($form->isSubmitted()){
           $em= $doctrine->getManager();
           $em->persist($transport);
           $em->flush();
           return $this-> redirectToRoute('showT');
       }
       return $this->render('transport/updateT.html.twig',[
           'form'=>$form->createView(),
       ]);
    }
    #[Route('/deleteT{id}', name: 'deleteT')]
    public function deleteT($id, TransportRepository $rep, ManagerRegistry $doctrine): Response
    {
        $em= $doctrine->getManager();
        $transport= $rep->find($id);

        foreach ($transport->getSubscriptions() as $subscription){
            $em->remove($subscription);
        }

        $em->remove($transport);
        $em->flush();
        return $this-> redirectToRoute('showT');
    }


    #[Route('/admin', name: 'app_back')]
    public function statss(
        UserRepository $userRepository,
        BookingRepository $bookingRepository,
        AccomodationRepository $accommodationRepository,
        ClaimsRepository $claimsRepository,
        CountryRepository $countryRepository,
        GuideRepository $guideRepository,
        MonumentRepository $monumentRepository,
        ReservationRepository $reservationRepository,
        SubscriptionRepository $subscriptionRepository,
        TransportRepository $transportRepository
    ): Response {
        $totalUsers = $userRepository->countTotalUsers();
        $totalBookings = $bookingRepository->countTotalBookings();
        $totalAccommodations = $accommodationRepository->countTotalAccommodations();
        $totalClaims = $claimsRepository->countTotalClaims();
        $totalCountries = $countryRepository->countTotalCountries();
        $totalGuides = $guideRepository->countTotalGuides();
        $totalMonuments = $monumentRepository->countTotalMonuments();
        $totalReservations = $reservationRepository->countTotalReservations();
        $totalSubscriptions = $subscriptionRepository->countTotalSubscriptions();
        $totalTransports = $transportRepository->countTotalTransports();

        return $this->render('BackOffice/backtest.html.twig', [
            'total_users' => $totalUsers,
            'total_bookings' => $totalBookings,
            'total_accommodations' => $totalAccommodations,
            'total_claims' => $totalClaims,
            'total_countries' => $totalCountries,
            'total_guides' => $totalGuides,
            'total_monuments' => $totalMonuments,
            'total_reservations' => $totalReservations,
            'total_subscriptions' => $totalSubscriptions,
            'total_transports' => $totalTransports,
        ]);
    }
}
