<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClaimsController extends AbstractController
{
    #[Route('/claims', name: 'app_claims')]
    public function index(): Response
    {
        return $this->render('claims/index.html.twig', [
            'controller_name' => 'ClaimsController',
        ]);
    }
}
