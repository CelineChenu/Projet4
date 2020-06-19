<?php


namespace App\Service;


use App\Controller\MailLog;
use App\Entity\Command;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class MailingService
{
    private $mailer;
    private $twig;

    public function __construct(EntityManagerInterface $manager, Environment $twig)
    {
        $this->manager = $manager;
        $this->twig = $twig;
    }

    public function sendMail($command)
    {

        $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername(MailLog::EMAIL)
            ->setPassword(MailLog::PASSWORD);
        $mailer = new \Swift_Mailer($transport);
        $message = (new \Swift_Message('Billetterie du Louvre'))
            ->setFrom(array('celine.chenu1@gmail.com' => 'Musée du Louvre'))
            ->setTo($command->getEmail())
            ->setBody(
                $this->twig->render('mail.html.twig', array('command' => $command)), 'text/html');
        $mailer->send($message);
    }

}