<?php

namespace App\Controller;

use App\Entity\Accomodation;
use App\Repository\AccomodationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FavsController extends AbstractController
{
    #[Route('/favorites', name: 'favorites_index')]
    public function index(SessionInterface $session, AccomodationRepository $accommodationRepository): Response
    {
        $favorites = $session->get("favorites", []);
        $favoriteAccommodations = $accommodationRepository->findBy(['refA' => array_keys($favorites)]);

        return $this->render('FrontOffice/favs.html.twig', [
            'favoriteAccommodations' => $favoriteAccommodations,
        ]);
    }

    #[Route('/favorites/add/{refA}', name: 'favorites_add')]
    public function addToFavorites(Accomodation $accommodation, SessionInterface $session): Response
    {
        $favorites = $session->get("favorites", []);
        $favorites[$accommodation->getRefA()] = $accommodation;
        $session->set("favorites", $favorites);

        return $this->redirectToRoute("showFac");
    }

    #[Route('/favorites/remove/{refA}', name: 'favorites_remove')]
    public function removeFromFavorites(Accomodation $accommodation, SessionInterface $session): Response
    {
        $favorites = $session->get("favorites", []);

        if (isset($favorites[$accommodation->getRefA()])) {
            unset($favorites[$accommodation->getRefA()]);
            $session->set("favorites", $favorites);
        }

        return $this->redirectToRoute("favorites_index");
    }

    #[Route('/favorites/clear', name: 'favorites_clear')]
    public function clearFavorites(SessionInterface $session): Response
    {
        $session->remove("favorites");
        return $this->redirectToRoute("favorites_index");
    }
}
