<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/', name: 'app_test')]
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
}
