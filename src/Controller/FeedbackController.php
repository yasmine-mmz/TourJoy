<?php

namespace App\Controller;

use App\Entity\Feedback;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FeedbackRepository;
use App\Repository\GuideRepository;

use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Component\HttpFoundation\Request;
use App\Form\FeedbackType;

class FeedbackController extends AbstractController
{
    #[Route('/feedback', name: 'app_feedback')]
    public function index(): Response
    {
        return $this->render('feedback/index.html.twig', [
            'controller_name' => 'FeedbackController',
        ]);
    }
    #[Route('/addfb', name: 'addfb')] 
    public function addAndFetchFeedbacks(ManagerRegistry $mr, FeedbackRepository $repo, Request $req): Response
    {
        // Handle adding feedback
        $f = new Feedback();
        $form = $this->createForm(FeedbackType::class, $f);
        $form->handleRequest($req);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($f);
            $em->flush();
            
            // Recreate the form to clear it
            $f = new Feedback();
            $form = $this->createForm(FeedbackType::class, $f);
        }
        
        // Fetch feedbacks
        $feedbacks = $repo->findAll();
    
        // Render the add.html.twig template with feedbacks and form
        return $this->render('feedback/add.html.twig', [
            'feedbacks' => $feedbacks,
            'f' => $form->createView(), // Pass the form to the template
        ]);
    }
    
    


    #[Route('/fetchb2{id}', name: 'fetchb2')]
    public function fetchb2(int $id, FeedbackRepository $repo, GuideRepository $guideRepository): Response
    {
        $guide = $guideRepository->find($id);
    
        if (!$guide) {
            throw $this->createNotFoundException('Guide not found');
        }
    
        // Fetch feedbacks for the selected guide
        $feedbacks = $repo->findBy(['fkGuide' => $guide]);
    
        return $this->render('feedback/show.html.twig', [
            'feedbacks' => $feedbacks,
        ]);
    }
    
    
    
}
