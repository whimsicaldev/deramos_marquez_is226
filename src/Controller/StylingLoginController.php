<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StylingLoginController extends AbstractController
{
    #[Route('/stylinglogin', name: 'app_styling_login')]
    public function index(): Response
    {
        return $this->render('styling_login/index.html.twig', [
            'controller_name' => 'StylingLoginController',
        ]);
    }
}
