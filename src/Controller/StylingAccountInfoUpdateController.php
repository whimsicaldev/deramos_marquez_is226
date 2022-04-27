<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StylingAccountInfoUpdateController extends AbstractController
{
    #[Route('/stylingaccountinfoupdate', name: 'app_styling_account_info_update')]
    public function index(): Response
    {
        return $this->render('styling_account_info_update/index.html.twig', [
            'controller_name' => 'StylingAccountInfoUpdateController',
        ]);
    }
}
