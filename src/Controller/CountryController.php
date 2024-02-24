<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CountryRepository;
use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Component\HttpFoundation\Request;
class CountryController extends AbstractController
{
    #[Route('/country', name: 'app_country')]
    public function index(): Response
    {
        return $this->render('BackOffice/showc.html.twig', [
            'controller_name' => 'CountryController',
        ]);
    }
    #[Route('/fetchc', name: 'country_show')]
    public function fetch(CountryRepository $repo, Request $request): Response
    {
        $searchValue = $request->query->get('search_value');
    
        if ($searchValue) {
            $result = $repo->searchByName($searchValue);
        } else {
            // If no search value provided, retrieve all countries
            $result = $repo->findAll();
        }
    
        return $this->render('BackOffice/showc.html.twig', [
            'Country' => $result,
        ]);
    }
    #[Route('/addc', name: 'addc')] 
    public function addc(ManagerRegistry $mr, Request $req): Response
    {
        $s = new Country();
        $form = $this->createForm(CountryType::class, $s);
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($s);
            $em->flush();
            
            return $this->redirectToRoute('country_show'); 
        }
        
        return $this->render('monument/addc.html.twig', [
            'f' => $form->createView()
        ]);
    }
    
    #[Route('/updatec{id}', name: 'updatec')]
    public function updateCountry(int $id, ManagerRegistry $mr, Request $req, CountryRepository $repo): Response
    {
        $s = $repo->find($id); 
    
        if (!$s) {
            throw $this->createNotFoundException('Country not found.');
        }
    
        $form = $this->createForm(CountryType::class, $s);     
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->flush();
    
            return $this->redirectToRoute('country_show'); 
        }
    
        return $this->render('monument/addc.html.twig', [
            'f' => $form->createView()
        ]);
    }
#[Route('/removec/{id}', name: 'removec')]
public function remove(CountryRepository $repo, $id, ManagerRegistry $mr):Response
{
    $country = $repo->find($id);


    $em = $mr->getManager();
        foreach($country->getMonuments() as $monument)
        {
    $em->remove($monument);
        }
    $em->remove($country);
    $em->flush();

    return $this ->redirectToRoute('country_show');
}


}
