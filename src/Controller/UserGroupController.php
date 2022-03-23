<?php

namespace App\Controller;

use App\Entity\UserGroup;
use App\Form\UserGroupType;
use App\Repository\UserGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/group')]
class UserGroupController extends AbstractController
{
    #[Route('/', name: 'app_user_group_index', methods: ['GET'])]
    public function index(UserGroupRepository $userGroupRepository): Response
    {
        return $this->render('user_group/index.html.twig', [
            'user_groups' => $userGroupRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserGroupRepository $userGroupRepository): Response
    {
        $userGroup = new UserGroup();
        $form = $this->createForm(UserGroupType::class, $userGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userGroupRepository->add($userGroup);
            return $this->redirectToRoute('app_user_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_group/new.html.twig', [
            'user_group' => $userGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_group_show', methods: ['GET'])]
    public function show(UserGroup $userGroup): Response
    {
        return $this->render('user_group/show.html.twig', [
            'user_group' => $userGroup,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserGroup $userGroup, UserGroupRepository $userGroupRepository): Response
    {
        $form = $this->createForm(UserGroupType::class, $userGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userGroupRepository->add($userGroup);
            return $this->redirectToRoute('app_user_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_group/edit.html.twig', [
            'user_group' => $userGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_group_delete', methods: ['POST'])]
    public function delete(Request $request, UserGroup $userGroup, UserGroupRepository $userGroupRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userGroup->getId(), $request->request->get('_token'))) {
            $userGroupRepository->remove($userGroup);
        }

        return $this->redirectToRoute('app_user_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
