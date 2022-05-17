<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StylingTermsAndConditionsController extends AbstractController
{
    #[Route('/termsandconditionsstyle', name: 'app_styling_terms_and_conditions')]
    public function index(): Response
    {
        return $this->render('styling_terms_and_conditions/index.html.twig', [
            'controller_name' => 'StylingTermsAndConditionsController',
        ]);
    }
}
