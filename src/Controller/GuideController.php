<?php

namespace App\Controller;

use App\Entity\Guide;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GuideRepository;
use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Component\HttpFoundation\Request;
use App\Form\GuideType;


class GuideController extends AbstractController
{
    #[Route('/guide', name: 'app_guide')]
    public function index(): Response
    {
        return $this->render('guide/index.html.twig', [
            'controller_name' => 'GuideController',
        ]);
    }

    #[Route('/fetch', name: 'fetch')] 
    public function fetch(GuideRepository $repo): Response
    {
        $result = $repo->findAll();

        return $this->render('guide/show.html.twig', [
            'guides' => $result,
        ]);
    }
    #[Route('/fetch2', name: 'fetch2')] 
    public function fetch2(GuideRepository $repo): Response
    {
        $result = $repo->findAll();

        return $this->render('guide/show2.html.twig', [
            'guides' => $result,
        ]);
    }
 
    #[Route('/addF', name: 'addF')] 
    public function addF(ManagerRegistry $mr,Request $req): Response
    {
       
        $g = new Guide();//-1instance
        $form=$this->createform(GuideType::class,$g);///2-
        $form->handleRequest($req);
        if($form->isSubmitted()&& $form->isValid()) {
            $em = $mr->getManager(); ///3- persist flush
            $em->persist($g);
            $em->flush();
            return $this ->redirectToRoute('fetch');    
        }
        
        return $this-> render('guide/add.html.twig',[
            'f'=>$form->createView()
        ]);
    }



    #[Route('/update{id}', name: 'update')]
    public function updateGuide(int $id, ManagerRegistry $mr, Request $req, GuideRepository $repo): Response
    {
        $s = $repo->find($id); // Find the student to update
    
        if (!$s) {
            throw $this->createNotFoundException('Guide not found.');
        }
        $form = $this->createForm(GuideType::class, $s); 
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
           
            $em->flush();
            return $this->redirectToRoute('fetch'); 
        }
        return $this->render('guide/add.html.twig', [
            'f' => $form->createView()
        ]);
    }
    
    #[Route('/remove/{id}', name: 'remove')]
    public function remove(GuideRepository $repo, $id, ManagerRegistry $mr): Response
    {
        $guide = $repo->find($id);
        if (!$guide) {
            throw $this->createNotFoundException('Guide not found');
        }
    
        $em = $mr->getManager();
            foreach ($guide->getFeedback() as $feedback) {
            $em->remove($feedback);
        }
    
        foreach ($guide->getBookings() as $booking) {
            $em->remove($booking);
        }
    
        $em->remove($guide);
        $em->flush();
    
        return $this->redirectToRoute('fetch');
    }



}
