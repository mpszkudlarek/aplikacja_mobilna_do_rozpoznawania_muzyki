<?php

namespace App\Controller;

use App\Exception\Entity\EntityNotFoundException;
use App\Exception\Json\InvalidJsonStructureException;
use App\Exception\Json\InvalidJsonPayloadException;
use App\Exception\User\Register\UserAlreadyExistsException;
use App\Service\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{

    /**
     * @throws InvalidJsonPayloadException
     * @throws UserAlreadyExistsException
     * @throws InvalidJsonStructureException
     * @throws EntityNotFoundException
     */
    #[Route('/register', name: 'register_user', methods: 'POST')]
    public function register(Request $requests, RegisterService $registerService): JsonResponse
    {
        return $registerService->register($requests);
    }
}