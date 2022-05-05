<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StylingAdminUpdateController extends AbstractController
{
    #[Route('/adminstyle', name: 'app_styling_admin_update')]
    public function index(): Response
    {
        return $this->render('styling_admin_update/index.html.twig', [
            'controller_name' => 'StylingAdminUpdateController',
        ]);
    }
}
