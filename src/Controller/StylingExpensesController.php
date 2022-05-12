<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StylingExpensesController extends AbstractController
{
    #[Route('/expensesstyle', name: 'app_styling_expenses')]
    public function index(): Response
    {
        return $this->render('styling_expenses/index.html.twig', [
            'controller_name' => 'StylingExpensesController',
        ]);
    }
}
