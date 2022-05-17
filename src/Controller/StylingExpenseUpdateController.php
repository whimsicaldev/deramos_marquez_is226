<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StylingExpenseUpdateController extends AbstractController
{
    #[Route('/expenseupdatestyle', name: 'app_styling_expense_update')]
    public function index(): Response
    {
        return $this->render('styling_expense_update/index.html.twig', [
            'controller_name' => 'StylingExpenseUpdateController',
        ]);
    }
}
