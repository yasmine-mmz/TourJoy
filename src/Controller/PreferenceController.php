<?php

namespace App\Controller;

use App\Form\PreferenceType;
use App\Repository\CountryRepository;
use App\Repository\AccomodationRepository;
use App\Repository\GuideRepository;
use App\Repository\TransportRepository;
use App\Repository\MonumentRepository;
use App\Repository\SubscriptionRepository; // Add this line
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;
class PreferenceController extends AbstractController
{
    private $countryRepository;
    private $accomodationRepository;
    private $guideRepository;
    private $transportRepository;
    private $monumentRepository;
    private $subscriptionRepository; // Add this line

    public function __construct(
        CountryRepository $countryRepository,
        AccomodationRepository $accomodationRepository,
        GuideRepository $guideRepository,
        TransportRepository $transportRepository,
        MonumentRepository $monumentRepository,
        SubscriptionRepository $subscriptionRepository // Add this line
    ) {
        $this->countryRepository = $countryRepository;
        $this->accomodationRepository = $accomodationRepository;
        $this->guideRepository = $guideRepository;
        $this->transportRepository = $transportRepository;
        $this->monumentRepository = $monumentRepository;
        $this->subscriptionRepository = $subscriptionRepository; // Add this line
    }
    #[Route('/subscription/pdf/{id}', name: 'subscription_pdf')]
public function generatePdfFront(Pdf $snappy, SubscriptionRepository $repository, int $id): Response
{
    $subscription = $repository->find($id);
    
    if (!$subscription) {
        throw $this->createNotFoundException('The subscription does not exist');
    }
    
    // Render the HTML content from the template
    $html = $this->renderView('subscription/Frontpdf.html.twig', [
        'subscription' => $subscription
    ]);

    // Convert the HTML content to PDF using Snappy
    $pdfContent = $snappy->getOutputFromHtml($html);

    // Replace any characters in the plan name that are not valid for a filename
    $safePlanName = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $subscription->getPlan());
    $filename = sprintf('Subscription-%s.pdf', $safePlanName);

    // Return the PDF as a downloadable attachment
    return new Response(
        $pdfContent,
        Response::HTTP_OK,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)
        ]
    );
}

    #[Route('/preferences', name: 'preferences')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(PreferenceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Debugging: Check if the form is submitted and valid
            dump('Form Submitted:', $form->isSubmitted());
            dump('Form Valid:', $form->isValid());

            // Check for form errors
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }

            if ($form->isValid()) {
                $preferences = $form->getData();

                $recommendedCountries = $this->countryRepository->findByRegionAndVisaRequired($preferences['region'], $preferences['visaRequired']);
                $recommendedGuides = $this->guideRepository->findByLanguage($preferences['language']);
                $recommendedTransports = $this->transportRepository->findByType($preferences['transportType']);
                $recommendedAccommodations = $this->accomodationRepository->findByType($preferences['accommodationType']);

                $recommendedSubscriptions = $this->subscriptionRepository->findByTransportType($preferences['region'], $preferences['transportType']);

                $monumentsByCountry = [];
                foreach ($recommendedCountries as $country) {
                    $monumentsByCountry[$country->getId()] = $this->monumentRepository->findBy(['fkcountry' => $country]);
                }
                $recommendedSubscriptions = $this->subscriptionRepository->findByTransportType(
                    $preferences['transportType']
                );
                return $this->render('recommendations.html.twig', [
                    'recommendedCountries' => $recommendedCountries,
                    'recommendedAccommodations' => $recommendedAccommodations,
                    'recommendedGuides' => $recommendedGuides,
                    'recommendedTransports' => $recommendedTransports,
                    'recommendedSubscriptions' => $recommendedSubscriptions, 
                    'monumentsByCountry' => $monumentsByCountry,
                ]);
            }
        }

        return $this->render('preferences.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
