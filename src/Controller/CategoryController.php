<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\DBAL\EnumCategoryType;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index', methods: ['GET', 'POST'])]
    public function index(CategoryRepository $categoryRepository, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        $toastMessage = $request->get('toastMessage')? $request->get('toastMessage'): false;
        $toastType = $request->get('toastType')? $request->get('toastType'): '';

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setType(EnumCategoryType::CATEGORY_EXPENSE);
            $categoryRepository->add($category);
            $toastMessage = 'Expense category created.';
            $toastType = 'success';
        }

        return $this->renderForm('category/index.html.twig', [
            'category' => $category,
            'form' => $form,
            'categories' => $categoryRepository->findAll(),
            'toastMessage' => $toastMessage,
            'toastType' => $toastType
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category);
            return $this->redirectToRoute('app_category_index', ['toastMessage' => 'Expense category updated.', 'toastType' => 'update'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $categoryRepository->remove($category);
        return $this->redirectToRoute('app_category_index', ['toastMessage' => 'Expense category deleted.', 'toastType' => 'update'], Response::HTTP_SEE_OTHER);
    }
}
