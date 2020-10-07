<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RiotGameAPIController extends AbstractController
{
    private $client;
    private $serverRegion = "euw1";
    private $https = "https://";
    private $baseurl = ".api.riotgames.com/lol/";
    private $endPointGetUserInfoBySummonerName = "summoner/v4/summoners/by-name/";
    private $endPointGetMatchList = "match/v4/matchlists/by-account/";
    private $endPointGetMatchInfo = "match/v4/matches/";
    private $token = "RGAPI-9212a8b3-bd8f-4e41-b256-184118db74b1";

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/riot/getHistoryMatchList/{sumonnerName}", name="getHistoryMatchList", methods={"GET"} )
     */
    public function getHistoryMatchList($sumonnerName) {
        $accountID = $this->getAccountID($sumonnerName);
        $url = $this->https.$this->serverRegion.$this->baseurl.$this->endPointGetMatchList.$accountID;
        $cb = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'X-Riot-Token' => $this->token
                ]
            ]
        );
        $response = new JsonResponse();
        $response->setContent($cb->getContent());
        return $response;
    }
    /**
     * @Route("/riot/getLast20Match/{sumonnerName}", name="getLast20Match", methods={"GET"} )
     */
    public function getLast20Match($sumonnerName) {
        $lastMatch = $this->getHistoryMatchList($sumonnerName);
        $lastMatch = json_decode($lastMatch->getContent(),true);
        $result = array_slice($lastMatch["matches"],0,20);
        //construction JSON
        $totalMatch = [];

        //TO DO GET MATCH
        foreach ($result as $match) {
            $match = $this->getInfoMatchByID($match["gameId"]);
            $match = json_decode($match->getContent(),true);
            array_push($totalMatch,$match);
        }
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);
        return JsonResponse::fromJsonString($serializer->serialize($totalMatch, 'json'));
    }

    /**
     * @Route("/riot/getHistoryMatch/{idMatch}", name="getHistoryMatch", methods={"GET"} )
     */
    public function getInfoMatchByID($idMatch){
        $url = $this->https.$this->serverRegion. $this->baseurl.$this->endPointGetMatchInfo.$idMatch;
        $cb = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'X-Riot-Token' => $this->token
                ]
            ]
        );
        $response = new JsonResponse();
        $response->setContent($cb->getContent());
        return $response;
    }

    private function getAccountID($summonerName) {
        $url = $this->https.$this->serverRegion. $this->baseurl.$this->endPointGetUserInfoBySummonerName.$summonerName;
        $response = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'X-Riot-Token' => $this->token
                ]
            ]
        );
        $responseContent =json_decode($response->getContent());
        return $responseContent->accountId;
    }
}
