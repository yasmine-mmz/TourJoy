<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    /**
     * @Route("/index", name="calendar")
     */
    public function index(ReservationRepository $reservation,$refA)
    {
        $events = $reservation->findBy(['name' => $refA]);

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getIdR(),
                'start' => $event->getStartDate()->format('Y-m-d'),
                'end' => $event->getEndDate()->format('Y-m-d'),
                'name' => $event->getName(),
                
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('calendar/index.html.twig', compact('data'));
    }
}