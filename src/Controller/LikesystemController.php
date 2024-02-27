<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Likesystem;
use App\Entity\User;
use App\Entity\Feedback;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\FeedbackRepository;
use App\Repository\LikesystemRepository;
use Symfony\Component\Security\Core\Security;


class LikesystemController extends AbstractController
{
    #[Route('/likesystem', name: 'app_likesystem')]
    public function index(): Response
    {
        return $this->render('likesystem/index.html.twig', [
            'controller_name' => 'LikesystemController',
        ]);
    }

    #[Route('/like-feedback', name: 'like_feedback')]
    public function likeFeedback(Request $request): RedirectResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('login'); // Ensure the user is logged in
        }
    
        $feedbackId = $request->request->get('feedback_id');
        $action = $request->request->get('action');
    
        $feedback = $this->getDoctrine()->getRepository(Feedback::class)->find($feedbackId);
        if (!$feedback) {
            return $this->redirectToRoute('addfb'); // Redirect if feedback is not found
        }
    
        $likeSystem = $this->getDoctrine()->getRepository(Likesystem::class)
                         ->findOneBy(['user' => $user, 'feedback' => $feedback]);
    
        if (!$likeSystem) {
            $likeSystem = new Likesystem();
            $likeSystem->setUser($user);
            $likeSystem->setFeedback($feedback);
            // If it's a new like/dislike, simply set the values based on the action
            $likeSystem->setUseful($action === 'useful' ? 1 : 0);
            $likeSystem->setNotUseful($action === 'not_useful' ? 1 : 0);
        } else {
            // If the like/dislike already exists, toggle the value based on the current state and action
            if ($action === 'useful' && $likeSystem->getUseful() === 1) {
                // If already marked as useful, toggle it off
                $likeSystem->setUseful(0);
            } elseif ($action === 'not_useful' && $likeSystem->getNotUseful() === 1) {
                // If already marked as not useful, toggle it off
                $likeSystem->setNotUseful(0);
            } else {
                // If toggling to a different state, set according to the action
                $likeSystem->setUseful($action === 'useful' ? 1 : 0);
                $likeSystem->setNotUseful($action === 'not_useful' ? 1 : 0);
            }
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($likeSystem);
        $entityManager->flush();

        
    
        return $this->redirectToRoute('addfb'); // Redirect back to the feedback page
    }
    
    #[Route('/feedback/view', name: 'feedback_view')]
    public function viewFeedback(FeedbackRepository $feedbackRepo, LikesystemRepository $likesystemRepo, Security $security): Response
    {
        $user = $security->getUser();

        // Redirect to login if not logged in
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $feedbacks = $feedbackRepo->findAll();

        // Prepare an array to hold user votes for feedback
        $userVotes = [];

        foreach ($feedbacks as $feedback) {
            $feedbackId = $feedback->getId();
            $likeSystem = $likesystemRepo->findOneBy(['user' => $user, 'feedback' => $feedback]);

            // Assume null means no vote, 1 means 'useful', and 0 means 'not useful'
            $userVotes[$feedbackId] = $likeSystem ? ($likeSystem->getUseful() ? 'useful' : 'not_useful') : null;
        }

        return $this->render('feedback/add.html.twig', [
            'feedbacks' => $feedbacks,
            'userVotes' => $userVotes,
        ]);
    }
   

}
