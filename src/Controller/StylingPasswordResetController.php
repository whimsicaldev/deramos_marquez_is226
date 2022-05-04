<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StylingPasswordResetController extends AbstractController
{
    #[Route('/passwordresetstyle', name: 'app_styling_password_reset')]
    public function index(): Response
    {
        return $this->render('styling_password_reset/index.html.twig', [
            'controller_name' => 'StylingPasswordResetController',
        ]);
    }
}
