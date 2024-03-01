<?php 

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Notification;
// use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use DateTime;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getGlobals(): array
    {
        $notifications = $this->entityManager->getRepository(Notification::class)
            ->findBy(['isRead' => false], ['createdAt' => 'DESC']);

        return [
            'notifications' => $notifications,
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('ago', [$this, 'timeAgo']),
        ];
    }

    public function timeAgo($dateTime)
    {
        if (!$dateTime instanceof \DateTimeInterface) {
            return "unknown time";
        }
    
        $interval = (new DateTime())->diff($dateTime);
    

        if ($interval->y > 0) {
            return $interval->y . ' years ago';
        } elseif ($interval->m > 0) {
            return $interval->m . ' months ago';
        } elseif ($interval->d > 0) {
            return $interval->d . ' days ago';
        } elseif ($interval->h > 0) {
            return $interval->h . ' hours ago';
        } elseif ($interval->i > 0) {
            return $interval->i . ' minutes ago';
        } else {
            return 'just now';
        }
    }
}