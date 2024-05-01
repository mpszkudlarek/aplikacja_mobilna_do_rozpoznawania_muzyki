<?php

namespace App\Service;

use App\Exception\Entity\EntityNotFoundException;
use App\Exception\Json\InvalidJsonStructureException;
use App\Exception\Json\InvalidJsonPayloadException;
use App\Exception\User\Register\UserAlreadyExistsException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User;

class RegisterService{
    private UserPasswordHasherInterface $userPasswordHasher;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private mixed $data;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @throws UserAlreadyExistsException
     * @throws InvalidJsonPayloadException
     * @throws InvalidJsonStructureException
     * @throws EntityNotFoundException
     */
    public function register(Request $request): JsonResponse{
        $this->data = json_decode($request->getContent(), true);
        $this->checkJsonStructure();
        $this->checkRegisterJsonData();
        $this->checkIfUserExists();
        $user = new User($this->data['username'], $this->data['password'], $this->userPasswordHasher);
        $this->validateUserEntity($user);
        $this->addUserToDatabase($user);

        return new JsonResponse(['message' => 'User successfully registered!'], Response::HTTP_CREATED);
    }

    /**
     * @throws InvalidJsonStructureException
     */
    private function checkJsonStructure(): void {
        if($this->data == null){
            throw new InvalidJsonStructureException();
        }
    }

    /**
     * @throws InvalidJsonPayloadException
     */
    private function checkRegisterJsonData(): void{
        if(!(array_key_exists('username', $this->data) &&
            array_key_exists('password', $this->data))){
            throw new InvalidJsonPayloadException();
        }
    }

    /**
     * @throws EntityNotFoundException
     */
    private function validateUserEntity(User $user): void{
        $errors = $this->validator->validate($user);
        $errorMessages = array();
        if (count($errors) > 0){
            foreach($errors as $error){
                $errorMessages[] = $error->getMessage();
            }
            throw new EntityNotFoundException(implode(',', $errorMessages));
        }
    }

    private function addUserToDatabase($user): void{
        $user->eraseCredentials();
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @throws UserAlreadyExistsException
     */
    private function checkIfUserExists(): void{
        $repository = $this->entityManager->getRepository(User::class);
        $userExists = $repository
            ->findOneBy(
                ['email' => $this->data['username']]
            );

        if(is_object($userExists)){
            throw new UserAlreadyExistsException("User with '" . $this->data['username'] . "' username already exists!");
        }
    }
}