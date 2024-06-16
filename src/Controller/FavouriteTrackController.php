<?php

namespace App\Controller;

use App\Entity\FavouriteTrack;
use App\Exception\Entity\EntityNotFoundException;
use App\Exception\Json\InvalidJsonPayloadException;
use App\Exception\Json\InvalidJsonStructureException;
use App\Repository\FavouriteTrackRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class FavouriteTrackController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     */
    #[Route('/favouritetrack/{id}', name: 'getFavouriteTrackById', methods: 'GET')]
    public function getById(int $id, FavouriteTrackRepository $repository): JsonResponse
    {
        $favouriteTrack = $repository->find($id);

        if($favouriteTrack == null){
            throw new EntityNotFoundException("Favourite track entity with id: " .$id. " not found");
        }


        $responseArray = [
            'id' => $favouriteTrack->getId(),
            'user_id' => $favouriteTrack->getUser()->getId(),
            'track_info' => $favouriteTrack->getTrackInfo(),
        ];

        return new JsonResponse($responseArray, Response::HTTP_OK);
    }

    /**
     * @throws EntityNotFoundException
     */
    #[Route('/favouritetrack/user/{id}', name: 'getFavouriteTrackByUserId', methods: 'GET')]
    public function getForUserId(int $id, FavouriteTrackRepository $repository): JsonResponse
    {
        $favouriteTracks = $repository->findBy(['user' => $id]);

        if(empty($favouriteTracks)){
            throw new EntityNotFoundException("Could not find any favourite track entity for given user id");
        }

        $response = array();

        foreach ($favouriteTracks as $favouriteTrack){
            $response[] = [
                'id' => $favouriteTrack->getId(),
                'user_id' => $favouriteTrack->getUser()->getId(),
                'track_info' => $favouriteTrack->getTrackInfo(),
            ];
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }

    #[Route('/favouritetrack/{id}', name: 'deleteFavouriteTrack', methods: 'DELETE')]
    public function deleteById(int $id, FavouriteTrackRepository $repository, EntityManagerInterface $manager): JsonResponse
    {
        $favouriteTrack = $repository->find($id);

        if($favouriteTrack == null){
            throw new EntityNotFoundException("Favourite track entity with id: " .$id. " not found");
        }

        $manager
            ->remove($favouriteTrack);
        $manager
            ->flush();

        return new JsonResponse(['message' => 'Entity deleted successfully'], Response::HTTP_OK);
    }

    /**
     * @throws EntityNotFoundException
     * @throws InvalidJsonStructureException
     * @throws InvalidJsonPayloadException
     */
    #[Route('/favouritetrack/', name: 'createFavouriteTrack', methods: 'POST')]
    public function addNewFavouriteTrack(Request $request, UserRepository $userRepository, EntityManagerInterface $manager): JsonResponse
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

        $favouriteTrack = new FavouriteTrack();
        $favouriteTrack->setTrackInfo($data['track_info']);
        $favouriteTrack->setUser($user);

        $manager->persist($favouriteTrack);
        $manager->flush();

        return new JsonResponse(['message' => 'Entity successfully added to database'], Response::HTTP_OK);
    }

}
