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
    public function fetch(CountryRepository $repo): Response
    {
        $result = $repo->findAll();
    
        return $this->render('BackOffice/showc.html.twig', [
            'Country' => $result,
        ]);
    }
    #[Route('/addc', name: 'addc')] 
    public function addc(ManagerRegistry $mr, CountryRepository $repo, Request $req): Response
    {
        $s = new Country();
        $form = $this->createForm(CountryType::class, $s);
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($s);
            $em->flush();
            
            return $this->redirectToRoute('country_show'); // Corrected route name
        }
        
        return $this->render('monument/addc.html.twig', [
            'f' => $form->createView()
        ]);
    }
    
    #[Route('/updatec/{id}', name: 'updatec')]
    public function updateCountry(int $id, ManagerRegistry $mr, Request $req, CountryRepository $repo): Response
    {
        $s = $repo->find($id); // Find the student to update
    
        if (!$s) {
            throw $this->createNotFoundException('Country not found.');
        }
    
        $form = $this->createForm(CountryType::class, $s); // Use the found student for the form
    
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            // You don't need to persist an existing entity, just flush
            $em->flush();
    
            return $this->redirectToRoute('country_show'); // Redirect to your list of students
        }
    
        return $this->render('monument/addc.html.twig', [
            'f' => $form->createView()
        ]);
    }
#[Route('/removec/{id}', name: 'removec')]
public function remove(CountryRepository $repo, $id, ManagerRegistry $mr):Response
{
    $monument = $repo->find($id);
    $em = $mr->getManager();
    $em->remove($monument);
    $em->flush();

    return $this ->redirectToRoute('country_show');
}


}
