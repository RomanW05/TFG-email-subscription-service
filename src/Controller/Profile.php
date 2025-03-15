<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Profile extends AbstractController
{
    public function mostrarCliente(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(
                ['error' => 'Not logged in'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $data = [
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ];

        return new JsonResponse([$data, Response::HTTP_OK]);
    }
}
