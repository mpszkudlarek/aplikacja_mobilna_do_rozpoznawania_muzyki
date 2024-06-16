<?php

namespace App\Controller;

use App\Exception\Recognition\NoRequiredFileException;
use App\Service\RecognitionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class MusicRecognitionController extends AbstractController
{
    private RecognitionService $recognitionService;

    public function __construct(RecognitionService $recognitionService)
    {
        $this->recognitionService = $recognitionService;
    }

    /**
     * @throws NoRequiredFileException
     */
    #[Route('/music/recognition', name: 'app_music_recognition')]
    public function index(Request $request): JsonResponse
    {

        $file = $request
            ->files
            ->get('file');

        if(!$file){
            throw new NoRequiredFileException();
        }

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $fileName = uniqid() . '.mp3';

        try {
            $file->move($uploadDir, $fileName);
        } catch (FileException $e) {
            return new JsonResponse(['error' => 'Failed to upload file'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $filePath = $uploadDir . '/' . $fileName;

        try {
            $apiResponse = $this->recognitionService->sendMp3FileToExtAPI($filePath);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to communicate with external API'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } finally {
            unlink($filePath);
        }

        return new JsonResponse($apiResponse);
    }
}
