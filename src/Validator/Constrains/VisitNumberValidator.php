<?php


namespace App\Validator\Constrains;

use App\Entity\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VisitNumberValidator extends ConstraintValidator
{
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function validate($value, Constraint $constraint)
    {
        $commandManager = $this->manager->getRepository(Command::class);
        $commands = $commandManager->findBy(['visitDay' => $value]);

        $totalTickets = 0;

        foreach ($commands as $command){
            $totalTickets += $command->getTicketNumber();
        }

        if ($totalTickets + $value->getTicketNumber() > 1000) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}