<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * retour d'une reponse contenant du html
     * @return Response
     */
    public function index():Response
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/contact")
     * @return Response
     */
    public function contact():Response
    {
        return $this->render('contact.html.twig');
    }
}
