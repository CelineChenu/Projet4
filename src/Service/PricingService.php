<?php

namespace App\Service;

use App\Entity\Command;
use App\Entity\Tariff;
use App\Repository\TariffRepository;
use Doctrine\ORM\EntityManagerInterface;

class PricingService
{
    private $manager;
    private $tariffsArray;
    private $minVal;
    private $maxVal;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->createTariffs();
    }

    public function createTariffs(){
        $tariffs = $this->manager->getRepository(Tariff::class)->findAll();

        foreach($tariffs as $tariff)
        {
            $this->tariffsArray[$tariff->getConstant()] = $tariff->getValue();
            $this->minVal[$tariff->getConstant()] = $tariff->getMinVal();
            $this->maxVal[$tariff->getConstant()] = $tariff->getMaxVal();
        }

    }

    public function getConstant(int $age){
        switch (true) {
            case ($age >= $this->minVal['FREE_PRICE'] && $age < $this->maxVal['FREE_PRICE']):
                $constant = 'FREE_PRICE';
                break;
            case ($age >= $this->minVal['CHILD_PRICE'] && $age < $this->maxVal['CHILD_PRICE']):
                $constant = 'CHILD_PRICE';
                break;
            case ($age >= $this->minVal['ADULT_PRICE'] && $age < $this->maxVal['ADULT_PRICE']):
                $constant = 'ADULT_PRICE';
                break;
            case ($age >= $this->minVal['SENIOR_PRICE'] && $age < $this->maxVal['SENIOR_PRICE']):
                $constant = 'SENIOR_PRICE';
                break;
            default :
                $constant = 'ADULT_PRICE';
        }
        return $constant;
    }

    public function calculTotal(Command $command){
        $total = 0;
        $tickets = $command->getTickets();

        foreach($tickets as $ticket){

            if ($ticket->getDiscountTicket() == false) {

                $visitDate = $command->getVisitDay();
                $age = date_diff($visitDate, $ticket->getBirthDate())->y;
                $constant = $this->getConstant($age);

            } else {
                $constant = 'DISCOUNT_PRICE';
            }

            $value = $this->tariffsArray[$constant];

            if($ticket->getDayTicket() == false)   $value = $value/2;

            $ticket->setPrice($value);
            $ticket->setType($constant);

            $total = $total + $value;
        }
        $command->setTotal($total);
        $this->manager->persist($command);
        $this->manager->flush();

        $this->manager->refresh($command);
        return $command;
    }
}
