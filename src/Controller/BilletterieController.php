<?php

namespace App\Controller;
use App\Entity\Command;
use App\Entity\Tariff;
use App\Entity\Ticket;
use App\Form\CommandType;
use App\Service\PricingService;
use App\Service\MailingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
                          'format' => 'dd/M/yyyy'
                      ])
                      ->add('ticketNumber')
                      ->add('email', EmailType::class)
                      ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            for ($i = 0; $i < $command->getTicketNumber(); $i++) {
                $command->addTicket(new Ticket());
            }
            $request->getSession()->set('command', $command);
            return $this->redirectToRoute("informations");
        }

        return $this->render("home/index.html.twig",['formCommand' => $form->createView()]);
    }

    /**
     * @Route("/informations", name="informations")
     */
    public function informations(Request $request, EntityManagerInterface $manager, PricingService $pricingService)
    {

        $command = $request->getSession()->get('command');

        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $command = $pricingService->calculTotal($command);
            return $this->redirectToRoute('recap', ["id"=>$command->getId()]);
        }

        return $this->render('informations.html.twig',['formInfo' => $form->createView()]);

    }

    /**
     * @Route("/recap/{id}", name="recap")
     */
    public function recap($id, EntityManagerInterface $manager){
        $command = $manager->getRepository(Command::class)->find($id);
        return $this->render('recap.html.twig', array('command' => $command));
    }

    /**
     * @Route("/paiement", name="paiement")
     */
    public function payment(Request $request, EntityManagerInterface $manager, MailingService $mailingService){

        $command = $request->getSession()->get('command');
        $total = $command->getTotal();
        $email = $command->getEmail();

        \Stripe\Stripe::setApiKey('sk_test_1lc0xordVRlD04bmsuEmEBKy00ZIA8cDE6');

        $intent = \Stripe\PaymentIntent::create([
            'amount' => $total * 100,
            'currency' => 'eur',
            // Verify your integration in this guide by including this parameter
            'metadata' => ['integration_check' => 'accept_a_payment'],
        ]);


        $str=rand();
        $code = sha1($str);
        $command->setCode($code);
        $manager->persist($command);
        $manager->flush();

        $mailingService->sendMail($command);

        return $this->redirectToRoute('validation');

    }

    /**
     * @Route("validation", name="validation")
     */
    public function validation(Request $request)
    {
        $command = $request->getSession()->get('command');
        return $this->render("validation.html.twig", array('command' => $command));
    }


    /**
     * @Route("/changelang_fr", name="changelang_fr")
     */
    public function changeLang_Fr(Request $request)
    {
        $request->getSession()->set('_locale', "fr");

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/changelang_en", name="changelang_en")
     */
    public function changeLang_En(Request $request)
    {
        $request->getLocale();
        $locale = setLocale(LC_ALL,'en');

        return $this->redirectToRoute('homepage', array('_locale' => $locale));
    }
}