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
        $data = json_decode($request->getContent(), true);

        $email    = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = new User();
        $user->setEmail($email);

        // using the recommended Symfony password hasher:
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $errors = $validator->validate($user);
        if (count($errors) > 0){
            $data = array("errors" => $errors, );
            return new JsonResponse(
                $data,
                Response::HTTP_CONFLICT // 409 status code
                );
        }
        

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['User created' => 'OK']);
    }

    public function get_user_data(Request $request): JsonResponse {
        $ip = $request->getClientIp();
        echo "The ip is: ". $ip. '
        ';
        echo $_SERVER['HTTP_USER_AGENT'].'
        ';
        $data = ['ip' => $ip];
        return new JsonResponse(
            $data,
            Response::HTTP_OK
            );
    }
}
?>