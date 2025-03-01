<?php

namespace App\Token;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

Class CustomToken {

    public function generate(JWTTokenManagerInterface $JWTManager, $payload, $user): string {
        $jwt = $JWTManager->createFromPayload($user, $payload);
        return $jwt;
    }
}
?>