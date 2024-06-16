<?php

namespace App\Service;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\Token\JWTPostAuthenticationToken;
use Symfony\Bundle\SecurityBundle\Security;

class JWTUserService {
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getUser(): ?User{
        $token = $this->security->getToken();

        if($token instanceof JWTPostAuthenticationToken){
            return $token->getUser();
        }
        return null;
    }
}