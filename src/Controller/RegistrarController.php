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
        // $data = json_decode($request->getContent(), true);

        // $email    = $data['email'] ?? '';
        // $password = $data['password'] ?? '';

        $user = new User();
        // $user->setEmail($email);

        // // using the recommended Symfony password hasher:
        // $hashedPassword = $passwordHasher->hashPassword($user, $password);
        // $user->setPassword($hashedPassword);

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
                return new JsonResponse(["Existing user" => 'Not registered']);
            }
            
            // Save user to database
            $em->persist($user);
            $em->flush();
            
            $this->addFlash('success', 'User created successfully');
            // return $this->redirectToRoute('some_success_page');
            return new JsonResponse(["All good" => 'OK']);
        }
        else {
            $errors = [];
    foreach ($form->getErrors(true) as $error) {
        $errors[] = $error->getMessage();
    }
    
    // For field-specific errors
    foreach ($form->all() as $childForm) {
        $childName = $childForm->getName();
        if ($childForm->getErrors()->count() > 0) {
            $childErrors = [];
            foreach ($childForm->getErrors() as $error) {
                $childErrors[] = $error->getMessage();
            }
            $errors[$childName] = $childErrors;
        }
    }
    
    return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        };


        return new JsonResponse(['User NOT created' => 'BUUU']);
    }

    public function mostrarFormulario(
        Request $request,
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrarType::class, $user);
        return $this->render('registrar/formulario.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
?>
