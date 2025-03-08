<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Profile extends AbstractController
{
    public function me(JWTTokenManagerInterface $JWTManager, TokenStorageInterface $tokenStorageInterface): JsonResponse
    {
        // getUser() returns the *fully authenticated* User object
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(
                ['error' => 'Not logged in'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $decodedJwtToken = $JWTManager->decode($tokenStorageInterface->getToken());
        echo $decodedJwtToken;

        // Return only the data you need, e.g. ID, email, username, roles, etc.
        // Adjust to your entity’s actual getters.
        return new JsonResponse([
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }
}