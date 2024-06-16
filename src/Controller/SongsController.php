<?php

namespace App\Controller;

use App\Exception\Entity\EntityNotFoundException;
use App\Repository\RecognitionHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SongsController extends AbstractController
{
    #[Route('/share/{id}', name: 'shareASong', methods: 'GET')]
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
}
