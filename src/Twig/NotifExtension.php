<?php

namespace App\Twig;

use Twig\TwigFunction;
use App\Entity\Trajet;
use App\Entity\User;
use App\Entity\Reservation;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;


class NotifExtension extends AbstractExtension {

    private $em;
    private $tokenStorage;


    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
      
    }

    public function getFunctions(): array {
        return[
            new TwigFunction('reser', [$this, 'getReservation'])
        ];
    }

    public function getReservation(){
        $trajet = $this->em->getRepository(Trajet::Class)->findBy(['user'=>$this->getUser()->getId()]);

        return $this->em->$reservation = $em->getRepository(Reservation::Class)->findBy(['trajet'=>$trajet]);
    }



}