<?php

namespace App\Controller;

use App\Entity\Reservation;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/event/add", name="api_event_add", methods={"POST"})
     */
    public function addEvent(Request $request)
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)
        ){
            // Les données sont complètes

            // On instancie un nouvel événement
            $event = new Reservation;

            // On hydrate l'objet avec les données
            $event->setTitle($donnees->title);
            $event->setDescription($donnees->description);
            $event->setStart(new DateTime($donnees->start));
            if(isset($donnees->end) && !empty($donnees->end)) {
                $event->setEnd(new DateTime($donnees->end));
            }
            $event->setAllDay(isset($donnees->allDay) ? $donnees->allDay : false);
            $event->setBackgroundColor($donnees->backgroundColor);
            $event->setBorderColor($donnees->borderColor);
            $event->setTextColor($donnees->textColor);

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            // On retourne une réponse
            return new Response('Event added successfully', 201);
        }else{
            // Les données sont incomplètes
            return new Response('Incomplete data', 400);
        }
    }
}
