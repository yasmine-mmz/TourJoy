<?php
namespace App\Controller;

use App\Entity\Accomodation;
use App\Entity\Favs;
use App\Repository\AccomodationRepository;
use App\Repository\FavsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class FavsController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/favorites', name: 'favorites_index')]
    public function index(FavsRepository $favsRepository): Response
    {
        $user = $this->security->getUser();
        $favoriteAccommodations = $favsRepository->findBy(['user' => $user]);

        return $this->render('FrontOffice/favs.html.twig', [
            'favoriteAccommodations' => $favoriteAccommodations,
        ]);
    }

    #[Route('/favorites/add/{refA}', name: 'favorites_add')]
    public function addToFavorites(Accomodation $accommodation): Response
    {
        $user = $this->security->getUser();
        if ($user) {
            $entityManager = $this->getDoctrine()->getManager();
            $favorite = new Favs();
            $favorite->setUser($user);
            $favorite->setAcc($accommodation);
            $entityManager->persist($favorite);
            $entityManager->flush();
        }

        return $this->redirectToRoute("showFac");
    }

    #[Route('/favorites/remove/{refA}', name: 'favorites_remove')]
    public function removeFromFavorites(Accomodation $accommodation, FavsRepository $favsRepository): Response
    {
        $user = $this->security->getUser();
        if ($user) {
            $favorite = $favsRepository->findOneBy(['user' => $user, 'name' => $accommodation]);
            if ($favorite) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($favorite);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute("favorites_index");
    }

    #[Route('/favorites/clear', name: 'favorites_clear')]
    public function clearFavorites(FavsRepository $favsRepository): Response
    {
        $user = $this->security->getUser();
        if ($user) {
            $favorites = $favsRepository->findBy(['user' => $user]);
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($favorites as $favorite) {
                $entityManager->remove($favorite);
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute("favorites_index");
    }
}