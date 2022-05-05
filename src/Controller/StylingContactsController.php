<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StylingContactsController extends AbstractController
{
    #[Route('/contactsstyle', name: 'app_styling_contacts')]
    public function index(): Response
    {
        return $this->render('styling_contacts/index.html.twig', [
            'controller_name' => 'StylingContactsController',
        ]);
    }
}
