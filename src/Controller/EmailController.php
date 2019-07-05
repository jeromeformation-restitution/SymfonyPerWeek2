<?php

namespace App\Controller;

use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    /**
     * @Route("/email", name="email")
     * @param Swift_Mailer $mailer
     * @return Response
     */
    public function index(Swift_Mailer $mailer)
    {
        $message = (new Swift_Message('Hello Email'))
            ->setFrom('doranco2019@gmail.com')
            ->setTo('doranco2019@gmail.com')
            ->setContentType('text/html')
            ->setBody(
                $this->renderView(
                    'sendemail.html.twig'
                )
            );
        $mailer->send($message);

        return $this->render('email/index.html.twig', [
            'controller_name' => 'EmailController',
        ]);
    }

}
