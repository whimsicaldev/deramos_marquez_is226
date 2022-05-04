<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StylingAccountUpdateController extends AbstractController
{
    #[Route('/stylingaccountupdate', name: 'app_styling_account_update')]
    public function index(): Response
    {
        return $this->render('styling_account_update/index.html.twig', [
            'controller_name' => 'StylingAccountUpdateController',
        ]);
    }
}
