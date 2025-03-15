<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use App\Form\RegistrarType;

class RegistrarController extends AbstractController{
    public function nuevoUsuario(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
    ): Response  {
        $user = new User();
        $form = $this->createForm(RegistrarType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $user->setRoles(['CLIENT']);
            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if ($existingUser) {
                $this->addFlash('error', 'User with this email already exists');
                return new JsonResponse(["El usuario ya existe" => 'No se ha registrado']);
            }

            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'User created successfully');
            $response = ["Nuevo usuario registrado correctamente" => 'OK'];

            return new JsonResponse($response, Response::HTTP_CREATED);
        }
        else {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
            $response = ['errors' => $errors];

            return new JsonResponse($response, Response::HTTP_BAD_REQUEST);
        };
    }

    public function mostrarFormulario(): Response {
        $user = new User();
        $form = $this->createForm(RegistrarType::class, $user);
        return $this->render('registrar/formulario.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
?>
