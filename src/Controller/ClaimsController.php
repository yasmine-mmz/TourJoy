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
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment as TwigEnvironment;
use Twig\Environment;
use Symfony\Contracts\EventDispatcher\Event;
use App\Entity\Claim; // Adjust namespace according to your application structure




class ClaimsController extends AbstractController
{

    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

   
    #[Route('/claims', name: 'app_claims')]
    public function index(): Response
    {
        return $this->render('claims/index.html.twig', [
            'controller_name' => 'ClaimsController',
        ]);
    }
    #[Route('/showclaims', name: 'Claims_show')]
public function show(ClaimsRepository $rep, Request $request): Response
{
    // Retrieve search term and sort parameters from the request
    $searchTerm = $request->query->get('search', '');
    $sortField = $request->query->get('sortField', 'id'); // Use a valid default field
    $sortOrder = $request->query->get('sortOrder', 'ASC'); // Default to 'ASC'

    // Fetch claims based on search criteria and sort parameters
    $claims = $rep->findBySearchCriteriaAndSort($searchTerm, $sortField, $sortOrder);

    // Check if the request is an AJAX request
    if ($request->isXmlHttpRequest()) {
        // If AJAX request, render the part of the template for the table body
        return $this->render('Claims/search.html.twig', [
            'Claimss' => $claims,
            'searchTerm' => $searchTerm,
            'currentSortField' => $sortField,
            'currentSortOrder' => $sortOrder,
        ]);
    }

    // Render the full page for non-AJAX requests
    return $this->render('Claims/index.html.twig', [
        'Claimss' => $claims,
        'searchTerm' => $searchTerm,
        'currentSortField' => $sortField,
        'currentSortOrder' => $sortOrder,
    ]);
}


    #[Route('/Claimsadd', name: 'Claims_add')]
    public function AddClaims(ManagerRegistry $doctrine, Request $request,ValidatorInterface $validator,
    MailerInterface $mailer): Response
    {

        $Claims =new Claims();
        $form=$this->createForm(ClaimsAddType::class,$Claims);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $Claims->getTitle(); // Assuming getTitle method exists in your Claims entity
        $description = $Claims->getDescription(); // Assuming getDescription method exists in your Claims entity

        $claims = [
            'title' => $request->request->get('title'),
            'description' => $request->request->get('description'),
            
            // Add more claim data as needed
        ];

            $Claims->setCreateDate(new \DateTimeImmutable());
            $em= $doctrine->getManager();
            $em->persist($Claims);
            $em->flush();
             // Send email notification to admin
             $email = (new Email())
             ->from('no-reply@tourjoy.com')
             ->to('admin@tourjoy.com')
             ->subject('New Claim Submitted')
             ->text("A new claim has been submitted. Title: {$Claims->getTitle()}, Description: {$Claims->getDescription()}");
         // Step 3: Send the email
         $mailer->send($email);
         

            return $this-> redirectToRoute('app_test');
        }
        return $this->render('Claims/Add.html.twig',[
            'Claims'=>$form->createView(),
        ]);
    }
    #[Route('/Claimsupdate{id}', name: 'Claims_update')]
    public function UpdateClaims(ManagerRegistry $doctrine, Request $request, ClaimsRepository $rep, $id,ValidatorInterface $validator): Response
    {
       $Claims = $rep->find($id);
       $form=$this->createForm(ClaimsUpdateType::class,$Claims);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
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
    #[Route('/Claimsstats', name: 'Claims_stats')]
public function ClaimsStats(ClaimsRepository $Rep): Response
{
    $ClaimsStats = $Rep->countClaimsByCategory();

    $labels = [];
    $data = [];
    foreach ($ClaimsStats as $stat) {
        $labels[] = $stat['categoryName'];
        $data[] = $stat['claimsCount'];
    }

    return $this->render('Claims/charts.html.twig', [
        'ClaimsStats' => $ClaimsStats,
        'labels' => $labels,
        'data' => $data,
    ]);
}
}
