<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PresentationController extends AbstractController
{
    /**
     * @Route("/presentation", name="presentation")
     */
    public function index()
    {
        $prenoms = ["Alicia" => 42, "Wejdene" => 16, "Maitre Gims" => 39];
        $age = 18;

        return $this->render('presentation/login.html.twig', [
            'controller_name' => 'PresentationController',
            'prenoms' => $prenoms,
            'age' => $age
        ]);
    }

    /**
     * @Route("/cv/{ecole}", name="cv", defaults={"ecole"= "NFactory"})
     */
    public function cv($ecole)
    {
        return $this->render('cv/login.html.twig', [
            'controller_name' => 'PresentationController',
            'ecole' => $ecole
        ]);
    }

    /**
     * @Route("/nfactory", name="nfactory")
     */
    public function nfactory()
    {
        return $this->render('nfactory/login.html.twig', [
            'controller_name' => 'PresentationController',
        ]);
    }

    /**
     * @Route("/age", name="age")
     */
    public function age()
    {
        $age = 42;
        $response = new Response();
        $response->setContent($age);
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }
}
