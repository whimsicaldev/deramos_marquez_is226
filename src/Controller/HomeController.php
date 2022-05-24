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

        if($user->isVerified()) {
            if($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_web_config_index', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_expense_index', [], Response::HTTP_SEE_OTHER);
            }

            // in prep for dashboard
            // return $this->render('home/index.html.twig', [
            //     'controller_name' => 'HomeController',
            // ]);
        } else {
            return $this->redirectToRoute('app_verify', [], Response::HTTP_SEE_OTHER);
        }
    }
}
