<?php

namespace App\Controller;
use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MonumentRepository;
use Symfony\Component\HttpFoundation\Request;

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


}
