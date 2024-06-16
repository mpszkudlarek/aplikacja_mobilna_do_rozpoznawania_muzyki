<?php

namespace App\Controller;

use App\Entity\RecognitionHistory;
use App\Exception\Entity\EntityNotFoundException;
use App\Exception\Json\InvalidJsonPayloadException;
use App\Exception\Json\InvalidJsonStructureException;
use App\Repository\RecognitionHistoryRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class RecognitionHistoryController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     */
    #[Route('/recognitionhistory/{id}', name: 'getRecognitionHistoryById', methods: 'GET')]
    public function getById(int $id, RecognitionHistoryRepository $repository): JsonResponse
    {
        $history = $repository->find($id);

        if($history == null){
            throw new EntityNotFoundException("Recognition history entity with id: " .$id. " not found");
        }


        $responseArray = [
            'id' => $history->getId(),
            'user_id' => $history->getUser()->getId(),
            'track_info' => $history->getTrackInfo(),
        ];

        return new JsonResponse($responseArray, Response::HTTP_OK);
    }

    /**
     * @throws EntityNotFoundException
     */
    #[Route('/recognitionhistory/user/{id}', name: 'getRecognitionHistoryByUserId', methods: 'GET')]
    public function getForUserId(int $id, RecognitionHistoryRepository $repository): JsonResponse
    {
        $recognitionHistory = $repository->findBy(['user' => $id]);

        if(empty($recognitionHistory)){
            throw new EntityNotFoundException("Could not find any recognition history entity for given user id");
        }

        $response = array();

        foreach ($recognitionHistory as $regognitionHistoryEntity){
            $response[] = [
                'id' => $regognitionHistoryEntity->getId(),
                'user_id' => $regognitionHistoryEntity->getUser()->getId(),
                'track_info' => $regognitionHistoryEntity->getTrackInfo(),
            ];
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @throws EntityNotFoundException
     */
    #[Route('/recognitionhistory/{id}', name: 'deleteRecognitionHistory', methods: 'DELETE')]
    public function deleteById(int $id, RecognitionHistoryRepository $repository, EntityManagerInterface $manager): JsonResponse
    {
        $recognitionHistory = $repository->find($id);

        if($recognitionHistory == null){
            throw new EntityNotFoundException("Recognition History entity with id: " .$id. " not found");
        }

        $manager
            ->remove($recognitionHistory);
        $manager
            ->flush();

        return new JsonResponse(['message' => 'Entity deleted successfully'], Response::HTTP_OK);
    }

    /**
     * @throws EntityNotFoundException
     * @throws InvalidJsonStructureException
     * @throws InvalidJsonPayloadException
     */
    #[Route('/recognitionhistory/', name: 'createRecognitionHistory', methods: 'POST')]
    public function addRecognitionHistory(Request $request, UserRepository $userRepository, EntityManagerInterface $manager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if($data == null){
            throw new InvalidJsonStructureException();
        }

        if(!array_key_exists('track_info', $data) || !array_key_exists('user_id', $data)){
            throw new InvalidJsonPayloadException();
        }

        $user = $userRepository->find($data['user_id']);

        if(!$user){
            throw new EntityNotFoundException("Cannot find user with given id");
        }

        $favouriteTrack = new RecognitionHistory();
        $favouriteTrack->setTrackInfo($data['track_info']);
        $favouriteTrack->setUser($user);
        $favouriteTrack->setRecognitionDate(new DateTimeImmutable());

        $manager->persist($favouriteTrack);
        $manager->flush();

        return new JsonResponse(['message' => 'Entity successfully added to database'], Response::HTTP_OK);
    }
}
