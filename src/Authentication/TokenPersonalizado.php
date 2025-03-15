<?php

namespace App\Authentication;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class TokenPersonalizado{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function nuevo_token(User $cliente, User $subscriptor): String
    {
        $fecha_caducidad = time() + 3600;
        $id_cliente = $cliente->getId();
        $payload = [
            'exp'=>$fecha_caducidad,
            'id_cliente'=>$id_cliente
        ];
        $jwt = $this->jwtManager->createFromPayload($subscriptor, $payload);
        return $jwt;
    }
}
?>
