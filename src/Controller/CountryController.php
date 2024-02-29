<?php

namespace App\Controller;
use MercurySeries\FlashyBundle\FlashyNotifier;
use App\Entity\Country;
use App\Form\CountryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CountryRepository;
use Doctrine\Persistence\ManagerRegistry; 
use Knp\Component\Pager\PaginatorInterface;
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
    public function fetch(CountryRepository $repo, Request $request,PaginatorInterface $paginator): Response
    {
        $searchValue = $request->query->get('search_value');
    
        if ($searchValue) {
            $result = $repo->searchByName($searchValue);
        } else {
            // If no search value provided, retrieve all countries
            $result = $repo->findAll();
        }
        $pagination = $paginator->paginate(
            $result, // The query or array to paginate.
            $request->query->getInt('page', 1), // Current page number, default to 1.
            5 // Limit per page.
        );
        
           
        return $this->render('BackOffice/showc.html.twig', [
            'pagination' => $pagination,
        
        ]);
    }
    #[Route('/addc', name: 'addc')] 
    public function addc(FlashyNotifier $flashy,ManagerRegistry $mr, Request $req): Response
    {
        $s = new Country();
        $form = $this->createForm(CountryType::class, $s);
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($s);
            $em->flush();
            $flashy->success('Country added succesfully','http://your-awesome-link.com');

            return $this->redirectToRoute('country_show'); 
        }
        
        return $this->render('BackOffice/addc.html.twig', [
            'f' => $form->createView()
        ]);
    }
    
    #[Route('/updatec{id}', name: 'updatec')]
    public function updateCountry(FlashyNotifier $flashy,int $id, ManagerRegistry $mr, Request $req, CountryRepository $repo): Response
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
            $flashy->success('Country updated succesfully','http://your-awesome-link.com');

            return $this->redirectToRoute('country_show'); 
        }
    
        return $this->render('BackOffice/addc.html.twig', [
            'f' => $form->createView()
        ]);
    }
#[Route('/removec/{id}', name: 'removec')]
public function remove(FlashyNotifier $flashy,CountryRepository $repo, $id, ManagerRegistry $mr):Response
{
    $country = $repo->find($id);


    $em = $mr->getManager();
        foreach($country->getMonuments() as $monument)
        {
    $em->remove($monument);
        }
    $em->remove($country);
    $em->flush();
    $flashy->success('Country deleted succesfully','http://your-awesome-link.com');

    return $this ->redirectToRoute('country_show');
}


}
