<?php

namespace App\Controller;
use App\Entity\Command;
use App\Entity\Ticket;
use App\Form\CommandType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BilletterieController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, EntityManagerInterface $manager)
    {
        $command = new Command();

        $form = $this->createFormBuilder($command)
                      ->add('visitDay',DateType::class, ['widget' => 'single_text','html5' => false,
                          'attr' => ['class' => 'js-datepicker'],])
                      ->add('ticketNumber')
                      ->add('email')
                      ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($command);
            return $this->redirectToRoute("informations");
        }

        return $this->render("home/index.html.twig",['formCommand' => $form->createView()]);
    }

    /**
     * @Route("/informations", name="informations")
     */
    public function informations(Request $request, EntityManagerInterface $manager)
    {
        $command = new Command();


        $ticket = new Ticket();
        $ticket->setFirstName('Prénom');
        $ticket->setLastName('Nom');
        $ticket->setCountry('Pays');
        $ticket->setBirthDate('\DateTimeInterface::ATOM','Date de naissance');
        $ticket->setDayTicket('Billet pour la journée');
        $ticket->setDiscountTicket('Tarif réduit');

        $command->getTickets()->add($ticket);



        $form = $this->createForm(CommandType::class, $command);

        $form->handleRequest($request);

        /* if ($form->isSubmitted() && $form->isValid()) {
            // ... maybe do some form processing, like saving the Task and Tag objects
        } */

        return $this->render('informations.html.twig',['formInfo' => $form->createView()]);

    }
}