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

class MonumentController extends AbstractController
{
    #[Route('/monument', name: 'app_monument')]
    public function index(): Response
    {
        return $this->render('monument/index.html.twig', [
            'controller_name' => 'MonumentController',
        ]);
    }
    #[Route('/fetch', name: 'monument_show')]
    public function fetch(MonumentRepository $repo, CountryRepository $countryRepository, Request $request): Response
    {
        $searchType = $request->query->get('search_type');
        $searchValue = $request->query->get('search_value');
        
        // Perform searches based on user input
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
    
  
    #[Route('/add', name: 'addF')] 
    public function addF(ManagerRegistry $mr, MonumentRepository $repo, Request $req): Response
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
    #[Route('/update/{id}', name: 'update')]
    public function updateMonument(int $id, ManagerRegistry $mr, Request $req, MonumentRepository $repo): Response
    {
        $s = $repo->find($id); // Find the student to update
    
        if (!$s) {
            throw $this->createNotFoundException('Monument not found.');
        }
    
        $form = $this->createForm(MonumentType::class, $s); // Use the found student for the form
    
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            // You don't need to persist an existing entity, just flush
            $em->flush();
    
            return $this->redirectToRoute('monument_show'); // Redirect to your list of students
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


   

    
}
