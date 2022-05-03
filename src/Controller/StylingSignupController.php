<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StylingSignupController extends AbstractController
{
    #[Route('/signupstyle', name: 'app_styling_signup')]
    public function index(): Response
    {
        return $this->render('styling_signup/index.html.twig', [
            'controller_name' => 'StylingSignupController',
        ]);
    }
}
