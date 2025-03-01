<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Login extends AbstractController{
    public function CreateUserAction(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {

        $data = json_decode($request->getContent(), true);

        $email    = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $user = new User();
        $user->setEmail($email);

        // using the recommended Symfony password hasher:
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();

        return new Response('User created');
    }
}
?>