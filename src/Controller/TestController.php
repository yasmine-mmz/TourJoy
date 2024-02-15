<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SubscriptionRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Subscription;
use Doctrine\Persistence\ManagerRegistry;

class TestController extends AbstractController
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('FrontOffice/front_template.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    
    #[Route('/back', name: 'app_back')]
    public function back(): Response
    {
        return $this->render('BackOffice/test.html.twig', [
            'controller_name' => 'BackController',
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
