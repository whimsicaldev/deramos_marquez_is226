<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
         // get the login error if there is one
         $error = $authenticationUtils->getLastAuthenticationError();

         // last username entered by the user
         $lastUsername = $authenticationUtils->getLastUsername();
         
         return $this->render('security/index.html.twig', [
             'last_username' => $lastUsername,
             'error'         => $error,
         ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/verify', name: 'app_verify')]
    public function verify(): Response 
    {
        return $this->render('security/verify.html.twig', [
            
        ]);
    }

    #[Route('/verify-email', name: 'app_verify_email')]
    public function verifyEmail(UserInterface $user = null, VerifyEmailHelperInterface $verifyEmailHelper, LoggerInterface $logger, MailerInterface $mailer): Response
    {
        $signatureComponents = $verifyEmailHelper->generateSignature(
            'app_verify',
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );
        
        $email = (new Email())
            ->from('no-reply@mayutangba.me')
            ->to('jesusgerardderamos@gmail.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!');

        $mailer->send($email);
        
        return $this->render('security/verify.html.twig', [
            
        ]);
    }
}