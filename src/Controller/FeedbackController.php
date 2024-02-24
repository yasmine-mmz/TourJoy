<?php

namespace App\Controller;

use App\Entity\Feedback;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FeedbackRepository;
use App\Repository\GuideRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($feedback);
            $em->flush();
        
            return $this->redirectToRoute('addfb'); // Redirect to prevent resubmission
        }
        
    
        $feedbacks = $repo->findAll();
    
        return $this->render('feedback/add.html.twig', [
            'feedbacks' => $feedbacks,
            'form' => $form->createView(),
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
    
    #[Route('/feedback/{id}/add-useful', name: 'feedback_add_useful', methods: ['POST'])]
    public function addUseful(Feedback $feedback, EntityManagerInterface $entityManager): Response
    {
        $feedback->setUseful(($feedback->getUseful() ?? 0) + 1);
        $entityManager->flush();

        return $this->redirectToRoute('addfb'); // Redirect to your feedback listing page
    }

    #[Route('/feedback/{id}/add-not-useful', name: 'feedback_add_not_useful', methods: ['POST'])]
    public function addNotUseful(Feedback $feedback, EntityManagerInterface $entityManager): Response
    {
        $feedback->setNotUseful(($feedback->getNotUseful() ?? 0) + 1);
        $entityManager->flush();

        return $this->redirectToRoute('addfb'); // Redirect to your feedback listing page
    }
    
    
}
