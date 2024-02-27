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
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;

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
    public function addF(FlashyNotifier $flashy,ManagerRegistry $mr, Request $req): Response
    {
        $monument = new Monument();
        $form = $this->createForm(MonumentType::class, $monument);
        
        $form->handleRequest($req);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($monument);
            $em->flush();
            $flashy->success('Monument added succesfully','http://your-awesome-link.com');

            return $this->redirectToRoute('monument_show');    
        }
        
        return $this->render('monument/add.html.twig', [
            'f' => $form->createView()
        ]);
    }
    #[Route('/update{id}', name: 'update')]
    public function updateMonument(FlashyNotifier $flashy,int $id, ManagerRegistry $mr, Request $req, MonumentRepository $repo): Response
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
            $flashy->success('Monument updated succesfully','http://your-awesome-link.com');

            return $this->redirectToRoute('monument_show'); 
        }
    
        return $this->render('monument/add.html.twig', [
            'f' => $form->createView()
        ]);
    }
#[Route('/remove/{id}', name: 'remove')]
public function remove(FlashyNotifier $flashy,MonumentRepository $repo, $id, ManagerRegistry $mr):Response
{
    $monument = $repo->find($id);
    $em = $mr->getManager();
    $em->remove($monument);
    $em->flush();
    $flashy->success('Monument deleted succesfully','http://your-awesome-link.com');
    return $this ->redirectToRoute('monument_show');
}
#[Route('/fetch', name: 'monument_show')]
public function fetch(MonumentRepository $repo, CountryRepository $countryRepository, Request $request, PaginatorInterface $paginator): Response
{
    $searchType = $request->query->get('search_type');
    $searchValue = $request->query->get('search_value');

    // Sorting parameters
    // $sortByAttribute = $request->query->get('sortByAttribute', 'nameM');
    // $sortOrder = $request->query->get('sortOrder', 'asc');
    // $orderBy = [$sortByAttribute => $sortOrder];

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

    // Apply sorting to the filtered result
    // $result = $repo->findBy([], $orderBy);

    $pagination = $paginator->paginate(
        $result, // The query or array to paginate.
        $request->query->getInt('page', 1), // Current page number, default to 1.
        5 // Limit per page.
    );

    // Pass the pagination object to the template.
    return $this->render('monument/show.html.twig', [
        'pagination' => $pagination,
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

    
    $filteredMonuments = array_unique($filteredMonuments, SORT_REGULAR);


    return $this->json($filteredMonuments);
}
#[Route('/back', name: 'monuments_per_country')]
public function monumentStats(MonumentRepository $monumentRepository): Response
{
    
    $monumentStats = $monumentRepository->countMonumentsByCountry();


    $labels = [];
    $data = [];
    foreach ($monumentStats as $stat) {
        $labels[] = $stat['country']; 
        $data[] = $stat['monumentCount']; 
    }

    return $this->render('BackOffice/back_template.html.twig', [
        'labels' => json_encode($labels),
        'data' => json_encode($data),
    ]);
}
}