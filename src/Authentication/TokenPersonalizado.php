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

    public function decodificar_token(string $token): array
    {
        $data = $this->jwtManager->parse($token);
        return $data;
    }

    public function validar_token(string $token): bool
    {
        $decoded_jwt = $this->decodificar_token($token);
        if ($decoded_jwt['exp'] <= time()){
            return false;
        }
        else{
            return true;
        }
    }
}
?>
