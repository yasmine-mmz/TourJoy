<?php

namespace App\Controller;

use App\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SubscriptionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SubscriptionType;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class SubscriptionController extends AbstractController
{
    #[Route('/subscription', name: 'app_subscription')]
    public function index(): Response
    {
        return $this->render('subscription/index.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }

    #[Route('/showS', name: 'showS')]
    public function show(SubscriptionRepository $rep): Response
    {
        $subscriptionList = $rep->findAll();
        return $this->render('subscription/index.html.twig', ['subscriptionList'=>$subscriptionList]);
    }

    


    #[Route('/createS', name: 'createS')]
    public function createS(ManagerRegistry $doctrine,Request $request): Response
    {
        $subscription = new Subscription();
        $form = $this->createForm(SubscriptionType::class, $subscription);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();
            $em->persist($subscription);
            $em->flush();
            return $this-> redirectToRoute('showS');
        }
        return $this->render('subscription/createS.html.twig',[
            'form' => $form->createView()
        ]);
    }


    #[Route('/updateS{id}', name: 'updateS')]
    public function UpdateS(ManagerRegistry $doctrine, Request $request, SubscriptionRepository $rep, $id,ValidatorInterface $validator): Response
    {
       $subscription = $rep->find($id);
       $form=$this->createForm(SubscriptionType::class,$subscription);
       $form->handleRequest($request);
       if($form->isSubmitted()){
        $errors = $validator->validate($subscription);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
    
            return new Response($errorsString);
        }

           $em= $doctrine->getManager();
           $em->persist($subscription);
           $em->flush();
           return $this-> redirectToRoute('showS');
       }
       return $this->render('subscription/updateS.html.twig',[
           'form'=>$form->createView(),
       ]);
    }

    #[Route('/deleteS{id}', name: 'deleteS')]
    public function deleteT($id, SubscriptionRepository $rep, ManagerRegistry $doctrine): Response
    {
        $em= $doctrine->getManager();
        $subscription= $rep->find($id);
        $em->remove($subscription);
        $em->flush();
        return $this-> redirectToRoute('showS');
    }
}
