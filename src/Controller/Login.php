<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class Login extends AbstractController{
    public function CreateUserAction(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ): JsonResponse {
        // $data = json_decode($request->getContent(), true);

        // $email    = $data['email'] ?? '';
        // $password = $data['password'] ?? '';

        // $user = new User();
        // $user->setEmail($email);

        // $hashedPassword = $passwordHasher->hashPassword($user, $password);
        // $user->setPassword($hashedPassword);

        // $errors = $validator->validate($user);
        // if (count($errors) > 0){
        //     $reponse = array("errors" => $errors, );
        //     return new JsonResponse(
        //         $reponse,
        //         Response::HTTP_CONFLICT
        //         );
        // }

        // $em->persist($user);
        // $em->flush();

        // $response = ['User created' => 'OK'];

        // return new JsonResponse($response, Response::HTTP_CREATED);
    }
}
?>
