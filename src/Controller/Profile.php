<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class Profile extends AbstractController
{
    public function me(): JsonResponse
    {
        // getUser() returns the *fully authenticated* User object
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(
                ['error' => 'Not logged in'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        // Return only the data you need, e.g. ID, email, username, roles, etc.
        // Adjust to your entity’s actual getters.
        return $this->json([
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }
}