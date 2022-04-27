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
use Symfony\Component\Form\FormError;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(UserPasswordHasherInterface $passwordHasher, Request $request, User $user, UserRepository $userRepository): Response
    {
        $updateMessage = null;
        $userForm = $this->createForm(UserType::class, $user);
        $userPasswordForm = $this->createForm(UserPasswordType::class, $user);

        $userForm->handleRequest($request);
        $userPasswordForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $existingUser = $userRepository->loadUserByIdentifier($user->getUsername());

            if($existingUser != null && $existingUser->getId() != $user->getId()) {
                $userForm->addError(new FormError('Username is already in use.'));
            } else {
                $userRepository->add($user);
                $updateMessage = "Profile successfully updated.";
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
            $updateMessage = "Password successfully changed.";
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'userForm' => $userForm,
            'userPasswordForm' => $userPasswordForm,
            'updateMessage' => $updateMessage
        ]);
    }

    #[Route('/{email}/view', name: 'app_user_search', methods: ['GET'])]
    #[Entity('user', expr: 'repository.loadUserByEmail(email)')]
    public function view(string $email, Request $request, UserRepository $userRepository, User $user = null): Response
    {
        return $this->renderForm('user/view.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
