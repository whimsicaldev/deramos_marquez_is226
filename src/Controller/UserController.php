<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(UserPasswordHasherInterface $passwordHasher, Request $request, User $user, UserRepository $userRepository): Response
    {
        $toast = null;
        $userForm = $this->createForm(UserType::class, $user);
        $userPasswordForm = $this->createForm(UserPasswordType::class, $user);

        $userForm->handleRequest($request);
        $userPasswordForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $existingUser = $userRepository->loadUserByIdentifier($user->getUsername());

            if($existingUser != null) {
                $form
                    ->get('username')
                    ->addError(new FormError('Username is already in use.'));
            } else {
                $userRepository->add($user);
                $toast = "Profile successfully updated.";
            }
        } else if ($userPasswordForm->isSubmitted() && $userPasswordForm->isValid()) {
            $plaintextPassword = $user->getPassword1();

            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            $userRepository->add($user);
            $toast = "Password successfully changed.";
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'userForm' => $userForm,
            'userPasswordForm' => $userPasswordForm,
            'toast' => $toast
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
