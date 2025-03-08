<?php

namespace App\Token;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

Class CustomToken {

    public function generate(JWTTokenManagerInterface $JWTManager, $user, $payload): string {
        $jwt = $JWTManager->createFromPayload($user, $payload);
        return $jwt;
    }

    public function decodeToken(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager): array {
        $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
        return $decodedJwtToken;
    }
}
?>