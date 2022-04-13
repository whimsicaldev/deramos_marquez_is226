<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UserInterface $user = null): Response
    {
        $twigTemplate = $user->isVerified()? 'home/index.html.twig': 'security/verify.html.twig';
        return $this->render($twigTemplate, [
            'controller_name' => 'HomeController',
        ]);
    }
}
