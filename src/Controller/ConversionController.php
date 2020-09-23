<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversionController extends AbstractController
{
    /**
     * @Route("/conversion", name="conversion")
     */
    public function index()
    {
        return $this->render('conversion/login.html.twig', [
            'controller_name' => 'ConversionController',
        ]);
    }

    /**
     * @Route
     * (
     *     "/celcius/{degres}/{sens}",
     *      name="celcius",
     *
     * )
     */
    public function celcius(int $degres,string $sens) {
        $response = new Response();
        if ($sens == "meters") {
            $response->setContent($this->transformCelciusToFaren($degres));
            $response->setStatusCode(Response::HTTP_OK);
        }elseif ($sens == "feet") {
            $response->setContent($this->transformFarenToCelcius($degres));
            $response->setStatusCode(Response::HTTP_OK);
        }else{
            $response->setContent('Bad request');
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }

    /**
     * @Route
     * (
     *     "/meter/{long}/{sens}",
     *      name="meter",
     *
     * )
     */
    public function meter(int $long, string $sens) {
        $response = new Response();
        if ($sens == "meters") {
            $response->setContent($this->metersToFeet($long));
            $response->setStatusCode(Response::HTTP_OK);
        }elseif ($sens == "feet") {
            $response->setContent($this->feetToMeters($long));
            $response->setStatusCode(Response::HTTP_OK);
        }else{
            $response->setContent('Bad request');
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }

    /**
     * @Route
     * (
     *     "/poids/{poids}/{sens}",
     *      name="poids",
     * )
     */
    public function poids(int $poids, string $sens) {
        $response = new Response();
        if ($sens == "kilo") {
            $response->setContent($this->kiloToLivre($poids));
            $response->setStatusCode(Response::HTTP_OK);
        }elseif ($sens== "livre") {
            $response->setContent($this->livreToKilo($poids));
            $response->setStatusCode(Response::HTTP_OK);
        }else {
            $response->setContent('Bad request');
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }

    private function transformCelciusToFaren(int $value){
        return ($value*9/5)+32;
    }

    private function  transformFarenToCelcius(int $faren) {
        return ($faren/9*5)-32;
    }

    private function metersToFeet($meters) {
        return floatval($meters) * 3.2808399;
    }

    private function feetToMeters(int $feet) {
        return floatval($feet)/ 2808399;
    }

    private function kiloToLivre($kilo) {
        return $kilo * 2.205;
    }

    private function livreToKilo($livre) {
        return $livre / 2.205;
    }
}
