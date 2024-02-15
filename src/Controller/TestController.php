<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SubscriptionRepository;

class TestController extends AbstractController
{
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
}
