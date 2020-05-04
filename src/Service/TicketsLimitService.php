<?php


namespace App\Service;

use App\Entity\Command;
use Doctrine\ORM\EntityManagerInterface;

class TicketsLimitService
{
    private $manager;
    private $ticketNumber;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function ticketsLimit(){
        $ticketsNumber = $this->manager->getRepository(Command::class)->findBy();
        
    }

}