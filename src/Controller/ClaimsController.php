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
use App\Form\ClaimsShowType;
use App\Entity\Claims;
use App\Entity\Notification;
use App\Repository\ClaimsRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment as TwigEnvironment;
use Twig\Environment;
use Symfony\Contracts\EventDispatcher\Event;
use App\Entity\Claim; // Adjust namespace according to your application structure
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\Security;


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
            
        ]);
    }

   
    // Render the full page for non-AJAX requests
    return $this->render('Claims/index.html.twig', [
        'Claimss' => $claims,
        'currentSortField' => $sortField,
        'currentSortOrder' => $sortOrder,
        // 'notifications' => $notifications,

    ]);
}


#[Route('/Claimsadd', name: 'Claims_add')]
public function AddClaims(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator, MailerInterface $mailer,Security $security): Response
{
    $Claims = new Claims();
    $user = $security->getUser();
    $firstName = $user->getFirstName();
   

    $form = $this->createForm(ClaimsAddType::class, $Claims);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $Claims->setFkU($user);
        $title = $Claims->getTitle(); // Assuming getTitle method exists in your Claims entity
        $description = $Claims->getDescription(); // Assuming getDescription method exists in your Claims entity

        $Claims->setCreateDate(new \DateTimeImmutable());


        $notification = new Notification();
       
        $notification->setMessage('A new claim has been submitted.');
        $notification->setIsRead(false);
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setUser($firstName);


        $em = $doctrine->getManager();
        $em->persist($Claims);
        $em->persist($notification);
        $em->flush();

        // Send email notification to admin
        $email = (new TemplatedEmail())
            ->from('no-reply@tourjoy.com')
            ->to('admin@tourjoy.com')   
            ->subject('New Claim Submitted')
            ->htmlTemplate('Claims/Mailing.html.twig')
            ->context([
                'title' => $title,
                'description' => $description
            ]);

        $mailer->send($email);

        return $this->redirectToRoute('Claims_showU');
    }
    return $this->render('Claims/Add.html.twig', [
        'Claims' => $form->createView(),
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
#[Route('/showclaimsU', name: 'Claims_showU')]
public function showU(ClaimsRepository $rep, Request $request, Security $security): Response
{
   $user=$security->getUser();
    // Fetch claims based on search criteria and sort parameters
    $claims = $rep->findByUser($user);

    return $this->render('Claims/show.html.twig', [
        'Claimss' => $claims,
        

    ]);
}

}
