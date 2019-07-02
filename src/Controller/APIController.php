<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{
    /**
     * @Route("/meteo", name="app_meteo")
     * @return JsonResponse
     */
    public function meteo(): JsonResponse
    {
        $today = [
            "temperature" => "35°C",
            "unite" => "celsius",
            "humidite" => "2%"
        ];
        return $this->json($today);
    }

    /**
     * @Route("/meteo2")
     * @return RedirectResponse
     */
    public function redirectMeteo(): RedirectResponse
    {
        return $this->redirectToRoute("app_meteo");
    }

    /**
     * @Route("/documentation")
     * @return BinaryFileResponse
     */
    public function pdf(): BinaryFileResponse
    {
        $pdf = new File("documents/support_cours.pdf");

        return $this->file($pdf, "bacary", ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
