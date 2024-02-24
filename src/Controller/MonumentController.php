<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Component\HttpFoundation\Request;
use App\Form\MonumentType;
use App\Entity\Monument;
use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MonumentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class MonumentController extends AbstractController
{
    #[Route('/monument', name: 'app_monument')]
    public function index(): Response
    {
        return $this->render('monument/index.html.twig', [
            'controller_name' => 'MonumentController',
        ]);
    }
    
  
    #[Route('/add', name: 'addF')] 
    public function addF(ManagerRegistry $mr, Request $req): Response
    {
        $monument = new Monument();
        $form = $this->createForm(MonumentType::class, $monument);
        
        $form->handleRequest($req);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($monument);
            $em->flush();
            
            return $this->redirectToRoute('monument_show');    
        }
        
        return $this->render('monument/add.html.twig', [
            'f' => $form->createView()
        ]);
    }
    #[Route('/update{id}', name: 'update')]
    public function updateMonument(int $id, ManagerRegistry $mr, Request $req, MonumentRepository $repo): Response
    {
        $s = $repo->find($id); 
    
        if (!$s) {
            throw $this->createNotFoundException('Monument not found.');
        }
    
        $form = $this->createForm(MonumentType::class, $s); 
    
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->flush();
    
            return $this->redirectToRoute('monument_show'); 
        }
    
        return $this->render('monument/add.html.twig', [
            'f' => $form->createView()
        ]);
    }
#[Route('/remove/{id}', name: 'remove')]
public function remove(MonumentRepository $repo, $id, ManagerRegistry $mr):Response
{
    $monument = $repo->find($id);
    $em = $mr->getManager();
    $em->remove($monument);
    $em->flush();

    return $this ->redirectToRoute('monument_show');
}
#[Route('/fetch', name: 'monument_show')]
public function fetch(MonumentRepository $repo, CountryRepository $countryRepository, Request $request): Response
{
    $searchType = $request->query->get('search_type');
    $searchValue = $request->query->get('search_value');
    
    switch ($searchType) {
        case 'name':
            $result = $repo->searchByName($searchValue);
            break;
        case 'price':
            $result = $repo->searchByEntryPrice($searchValue);
            break;
        case 'country':
            $result = $repo->searchByCountry($searchValue);
            break;
        default:
            $result = $repo->findAll();
    }

    $countries = $countryRepository->findAll();

    return $this->render('monument/show.html.twig', [
        'Monuments' => $result,
        'countries' => $countries,
    ]);
}

#[Route('/monuments/search', name: 'monuments_search', methods: ['POST'])]
public function searchMonuments(Request $request, MonumentRepository $monumentRepository): Response
{
    $name = $request->request->get('name');
    $country = $request->request->get('country');
    $price = $request->request->get('price');

    // Initialize an empty array to hold filtered monuments
    $filteredMonuments = [];

    // Search by name if provided
    if ($name) {
        $filteredMonuments = $monumentRepository->searchByName($name);
    }

    // Filter by entry price if provided
    if ($price) {
        $priceFilteredMonuments = $monumentRepository->searchByEntryPrice($price);
        // Merge the results with existing filteredMonuments
        $filteredMonuments = array_merge($filteredMonuments, $priceFilteredMonuments);
    }

    // Filter by country if provided
    if ($country) {
        $countryFilteredMonuments = $monumentRepository->searchByCountry($country);
        // Merge the results with existing filteredMonuments
        $filteredMonuments = array_merge($filteredMonuments, $countryFilteredMonuments);
    }

    // Remove duplicate entries from the merged results
    $filteredMonuments = array_unique($filteredMonuments, SORT_REGULAR);

    // Convert the filtered monuments to JSON and return the response
    return $this->json($filteredMonuments);
}
#[Route('/back', name: 'monuments_per_country')]
public function monumentStats(MonumentRepository $monumentRepository): Response
{
    // Get the statistics from the repository
    $monumentStats = $monumentRepository->countMonumentsByCountry();

    // Prepare the labels and data for the chart
    $labels = [];
    $data = [];
    foreach ($monumentStats as $stat) {
        $labels[] = $stat['country']; // Make sure 'country' is the correct key
        $data[] = $stat['monumentCount']; // Make sure 'monumentCount' is the correct key
    }

    return $this->render('BackOffice/back_template.html.twig', [
        'labels' => json_encode($labels),
        'data' => json_encode($data),
    ]);
}
}