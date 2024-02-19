<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddToFavsController extends AbstractController
{
    #[Route('/add/to/favs', name: 'app_add_to_favs')]
    public function index(): Response
    {
        return $this->render('add_to_favs/index.html.twig', [
            'controller_name' => 'AddToFavsController',
        ]);
    }
}
