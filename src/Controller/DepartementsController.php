<?php

namespace App\Controller;

use App\Entity\Departements;
use App\Repository\DepartementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;

class DepartementsController extends AbstractController
{
    /**
     * @Route("/departements", name="departements")
     */
    public function index(DepartementsRepository $departementsRepository)
    {
        return $this->render('departements/index.html.twig', [
            'controller_name' => 'PresentationController',
            'departements' => $departementsRepository->findAll()
        ]);
    }

    /**
     * @Route("/departements/json", name="indexJson")
     * @param DepartementsRepository $departementsRepository
     */
    public function indexJson(DepartementsRepository $departementsRepository)
    {
        $departements = $departementsRepository->findAll();
        $jsonContent = $this->serializeDepartement($departements);
        $response = JsonResponse::fromJsonString($jsonContent);
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }

    /**
     * @Route("/departements/{slug}", name="getdepartementbyslug")
     */
    public function getdepartementbyslug(string $slug,DepartementsRepository $departementsRepository)
    {
        return $this->render('departements/index.html.twig', [
            'controller_name' => 'PresentationController',
            'departements' => $departementsRepository->findBy(['slug' => $slug])
        ]);
    }



    /**
     * @Route("/departements/json/{slug}", name="jsongetdepartementbyslug")
     * @param Departements $departement
     * @return JsonResponse
     */
    public function jsongetdepartementbyslug(Departements $departement)
    {
        return JsonResponse::fromJsonString($this->serializeDepartement($departement));
    }

    /**
     * @Route("/departements/json/numero/{numero}", name="jsongetdepartementbynumero")
     * @param Departements $departement
     * @return JsonResponse
     */
    public function jsongetdepartementbynumero(Departements $departement)
    {
        return JsonResponse::fromJsonString($this->serializeDepartement($departement));
    }

    /**
     * @Route("/v2/json/departments", name="jsonv2Departments", methods={"GET"} )
     */
    public function jsonv2Departments(Request $request, DepartementsRepository $departementsRepository)
    {
        $filter = [];
        $em = $this->getDoctrine()->getManager();
        $metaData = $em->getClassMetadata(Departements::class)->getFieldNames();
        foreach ($metaData as $value) {
            $filter[$value] = $request->query->get($value);
        }
        return JsonResponse::fromJsonString($this->serializeDepartement($departementsRepository->findBy($filter)));
    }

    /**
     * @Route("/v2/json/departments", name="department_create", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function departmentCreate(Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $nom = $request->request->get('name');

        $departement = new Departements();
        $departement->setName($request->request->get('name','undefined'))
            ->setNumero($request->request->get('numero',0))
            ->setDensite($request->request->get('densite',0))
            ->setDescriptions($request->request->get('descriptions','undefined'))
            ->setSuperficie($request->request->get('densite',0));

        $entityManager->persist($departement);
        $entityManager->flush();

        $response = new Response();
        $response->setContent("Creation of department with id " . $departement->getId());
        return $response;
    }

    private function serializeDepartement($objet){
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getSlug();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);

        return $serializer->serialize($objet, 'json');
    }
}
