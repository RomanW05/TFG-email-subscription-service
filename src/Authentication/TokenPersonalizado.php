<?php

namespace App\Authentication;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TokenPersonalizado{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function nuevo_token(User $cliente, User $subscriptor): String
    {
        $email = $subscriptor->getEmail();
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
