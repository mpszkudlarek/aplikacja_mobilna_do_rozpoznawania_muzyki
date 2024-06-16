<?php

namespace App\Service;
use App\Entity\RecognitionHistory;
use App\Exception\User\CannotGetUserFromTokenException;
use DateTimeImmutable;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;

class RecognitionService{
    private array $headers = [
        'x-rapidapi-key' => '408298ea21msh305a377cb64bf2bp1bd927jsn021b58564038',
        'x-rapidapi-host' => 'music-identify.p.rapidapi.com',
        'Content-Type' => 'multipart/form-data;'
    ];
    private String $url = 'https://music-identify.p.rapidapi.com/identify';
    private HttpClientInterface $httpClient;
    private EntityManagerInterface $entityManager;

    public JWTUserService $JWTUserService;


    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager, JWTUserService $JWTUserService )
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->JWTUserService = $JWTUserService;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws CannotGetUserFromTokenException
     */
    public function sendMp3FileToExtAPI(string $filePath): array{

        $user = $this->JWTUserService->getUser();

        if($user == null){
            throw new CannotGetUserFromTokenException();
        }

        $response = $this->httpClient->request('POST', $this->url, [
            'headers' => $this->headers
        ,
        'body' => [
            'file' => fopen($filePath, 'r')
        ]]);



        $recognitionHistory = new RecognitionHistory();
        $recognitionHistory->setTrackInfo($response->toArray());
        $recognitionHistory->setUser($user);
        $recognitionHistory->setRecognitionDate(new DateTimeImmutable());

        $this->entityManager->persist($recognitionHistory);
        $this->entityManager->flush();


        return $response->toArray();
    }



}