<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ClaimsType;
use App\Form\ClaimsAddType;
use App\Form\ClaimsUpdateType;
use App\Entity\Claims;
use App\Repository\ClaimsRepository;


class ClaimsController extends AbstractController
{
    #[Route('/claims', name: 'app_claims')]
    public function index(): Response
    {
        return $this->render('claims/index.html.twig', [
            'controller_name' => 'ClaimsController',
        ]);
    }
    #[Route('/showclaims', name: 'Claims_show')]
    public function show(ClaimsRepository $rep): Response
    {
        $Claimss = $rep->findAll();
        return $this->render('Claims/index.html.twig', ['Claimss'=>$Claimss]);
    }
    #[Route('/Claimsadd', name: 'Claims_add')]
    public function AddClaims(ManagerRegistry $doctrine, Request $request): Response
    {
        $Claims =new Claims();
        $form=$this->createForm(ClaimsAddType::class,$Claims);
        $form->handleRequest($request);
        if($form->isSubmitted()){

            $Claims->setCreateDate(new \DateTimeImmutable());
            $em= $doctrine->getManager();
            $em->persist($Claims);
            $em->flush();
            return $this-> redirectToRoute('Claims_show');
        }
        return $this->render('Claims/Add.html.twig',[
            'Claims'=>$form->createView(),
        ]);
    }
    #[Route('/Claimsupdate{id}', name: 'Claims_update')]
    public function UpdateClaims(ManagerRegistry $doctrine, Request $request, ClaimsRepository $rep, $id): Response
    {
       $Claims = $rep->find($id);
       $form=$this->createForm(ClaimsUpdateType::class,$Claims);
       $form->handleRequest($request);
       if($form->isSubmitted()){
           $em= $doctrine->getManager();
           $em->persist($Claims);
           $em->flush();
           return $this-> redirectToRoute('Claims_show');
       }
       return $this->render('Claims/Update.html.twig',[
           'Claims'=>$form->createView(),
       ]);
    }
    #[Route('/Claimsdelete{id}', name: 'Claims_delete')]
    public function deleteClaims($id, ClaimsRepository $rep, ManagerRegistry $doctrine): Response
    {
        $em= $doctrine->getManager();
        $Claims= $rep->find($id);
        $em->remove($Claims);
        $em->flush();
        return $this-> redirectToRoute('Claims_show');
    }
}
