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
use Knp\Snappy\Pdf;


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
       if ($form->isSubmitted() && $form->isValid()) {
       

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
    #[Route('/chart', name: 'chart')]
    public function chartData(SubscriptionRepository $repository): Response
{
    $subscriptions = $repository->findAll();
    $durationCounts = [];

    // Count the number of plans for each duration
    foreach ($subscriptions as $subscription) {
        $duration = $subscription->getDuration();
        if (!isset($durationCounts[$duration])) {
            $durationCounts[$duration] = 0;
        }
        $durationCounts[$duration]++;
    }

    // Prepare data for the chart
    $chartData = [];
    foreach ($durationCounts as $duration => $count) {
        $chartData[] = ['name' => $duration . ' days', 'y' => $count];
    }

    return $this->render('subscription/chart.html.twig', [
        'data' => json_encode(array_values($chartData)),
    ]);
}
#[Route('/subscription/pdf/{id}', name: 'subscription_pdf')]
public function generatePdf(Pdf $snappy, SubscriptionRepository $repository, int $id): Response
{
    $subscription = $repository->find($id);
    
    if (!$subscription) {
        throw $this->createNotFoundException('The subscription does not exist');
    }
    
    $html = $this->renderView('subscription/pdf.html.twig', [
        'subscription' => $subscription
    ]);

    $pdfContent = $snappy->getOutputFromHtml($html);

    // Replace any characters in the plan name that are not valid for a filename
    $safePlanName = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $subscription->getPlan());
    $filename = sprintf('Subscription-%s.pdf', $safePlanName);

    return new Response(
        $pdfContent,
        Response::HTTP_OK,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)
        ]
    );
}


}
