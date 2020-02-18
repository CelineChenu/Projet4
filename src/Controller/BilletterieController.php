<?php

namespace App\Controller;
use App\Entity\Command;
use App\Entity\Tariff;
use App\Entity\Ticket;
use App\Form\CommandType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class BilletterieController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, EntityManagerInterface $manager)
    {
        $command = new Command();

        $form = $this->createFormBuilder($command)
                      ->add('visitDay',DateType::class, [
                          'widget' => 'single_text',
                          'html5' => false,
                          'attr' => ['class' => 'js-datepicker'],
                          'format' => 'dd/mm/yyyy'
                      ])
                      ->add('ticketNumber')
                      ->add('email', EmailType::class)
                      ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $request->getSession()->set('command', $command);
            return $this->redirectToRoute("informations");
        }

        return $this->render("home/index.html.twig",['formCommand' => $form->createView()]);
    }

    /**
     * @Route("/informations", name="informations")
     */
    public function informations(Request $request, EntityManagerInterface $manager)
    {

        $command = $request->getSession()->get('command');
        $ticketNumber = $command->getTicketNumber();

        for ($i = 0; $i < $ticketNumber; $i++) {
            $command->addTicket(new Ticket());
        }

        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            $total = 0;
            $tariffs = $manager->getRepository(Tariff::class)->findAll();

            foreach($tariffs as $tariff)
            {
                $tariffsArray[$tariff->getConstant()] = $tariff->getValue();
                $minVal[$tariff->getConstant()] = $tariff->getMinVal();
                $maxVal[$tariff->getConstant()] = $tariff->getMaxVal();
            }

            $tickets = $command->getTickets();

            foreach($tickets as $ticket){

                $currentDate = new \DateTime();
                $age = date_diff($currentDate, $ticket->getBirthDate()) -> y;

                switch ($age)
                {
                    case ($age >= $minVal['CHILD_PRICE'] && $age < $maxVal['CHILD_PRICE']):
                        {$constant = 'CHILD_PRICE';}
                        break;
                    case ($age >= $minVal['ADULT_PRICE'] && $age < $maxVal['ADULT_PRICE']):
                        {$constant = 'ADULT_PRICE';}
                        break;
                    case ($age >= $minVal['SENIOR_PRICE'] && $age < $maxVal['SENIOR_PRICE']):
                        {$constant = 'SENIOR_PRICE';}
                        break;
                }
                $value = $tariffsArray[$constant];


                if($command->getDayTicket() == 0)   $value = $value/2;

                $ticket->setPrice($value);
                $ticket->setType($constant);

                $total = $total + $value;
            }

            $command->setTotal($total);

        }

        return $this->render('informations.html.twig',['formInfo' => $form->createView()]);

    }
}