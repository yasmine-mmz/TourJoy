<?php

namespace App\Controller;
use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MonumentRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SubscriptionRepository;

use App\Entity\Subscription;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Notification;


class TestController extends AbstractController
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    #[Route('/', name: 'app_test')]
    public function index(): Response
    {
        $user =$this->getUser();
        $isVerified = $user && !$user->IsVerified();

        return $this->render('FrontOffice/front_template.html.twig', [
            'controller_name' => 'TestController',
            'isVerified' => $isVerified,
        ]);
    }
    #[Route('/admin', name: 'app_back')]
    public function back(): Response
    {

        $notifications = $this->getDoctrine()->getRepository(Notification::class)->findBy(['isRead' => false], ['createdAt' => 'DESC']);


        return $this->render('BackOffice/back_template.html.twig', [
            'controller_name' => 'BackController',
            'notifications' => $notifications,
        ]);
        
    }
    #[Route('/layouts', name: 'app_layouts')]
    public function layouts(): Response
    {
        return $this->render('BackOffice/forms-layouts.html.twig', [
            'controller_name' => 'BackController',
        ]);
    } 
    
    #[Route('/service', name: 'app2_test')]
    public function service(): Response
    {
        return $this->render('FrontOffice/test.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    
    #[Route('/all_monuments', name: 'all_monuments')]
    public function allMonumentsPage(MonumentRepository $repo, CountryRepository $countryRepository, Request $request): Response
    {
        $sortByPrice = $request->query->get('sort_by_price');
        $searchType = $request->request->get('search_type');
        $searchValue = $request->request->get('search_value');
        
        if ($sortByPrice === 'true') {
            $result = $repo->findAllSortedByPrice();
        } elseif ($searchType && $searchValue) {
            switch ($searchType) {
                case 'name':
                    $result = $repo->searchByName($searchValue);
                    break;
                case 'country':
                    $result = $repo->searchByCountry($searchValue);
                    break;
                case 'price':
                    $result = $repo->searchByEntryPrice($searchValue);
                    break;
                default:
                    $result = $repo->findAll();
            }
        } else {
            $result = $repo->findAll();
        }
        
        $countries = $countryRepository->findAll();
        
        return $this->render('monument/all_monuments.html.twig', [
            'Monuments' => $result,
            'countries' => $countries,
        ]);
    }
    
    #[Route('/details{id}', name: 'details')]
    public function details(MonumentRepository $monumentRepository, $id): Response
    {
        $monument = $monumentRepository->findOneById($id);
    
        return $this->render('monument/monument_details.html.twig', [
            'monument' => $monument,
        ]);
    }
    #[Route('/showSF', name: 'showSF')]
    public function showF(SubscriptionRepository $rep): Response
    {
        $subscriptionList = $rep->findAll();
        return $this->render('subscription/subscriptionsF.html.twig', ['subscriptionList'=>$subscriptionList]);
    }
    
    #[Route('/mySubscriptions', name: 'mySubscriptions')]
    public function mySubs(SubscriptionRepository $rep): Response
    {
        $subscriptionList = $rep->findAll();
        return $this->render('subscription/mySubscriptions.html.twig', ['subscriptionList' => $subscriptionList]);
    }

    #[Route('/choose-subscription', name: 'choose_subscription')]
    public function chooseSubscription(Request $request, SubscriptionRepository $rep): Response
    {
        $subscriptionList = $rep->findAll();
        $selectedSubscriptionId = $request->request->get('selected_subscription');

        
        $entityManager = $this->registry->getManager();
        $selectedSubscription = $entityManager->getRepository(Subscription::class)->find($selectedSubscriptionId);

        
        return $this->render('subscription/chosensub.html.twig', [
            'selectedSubscription' => $selectedSubscription,
            
        ]);
    }


}
