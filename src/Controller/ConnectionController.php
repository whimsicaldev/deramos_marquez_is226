<?php

namespace App\Controller;

use App\Entity\Connection;
use App\Form\ConnectionType;
use App\Repository\ConnectionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\DBAL\EnumConnectionType;

#[Route('/connection')]
class ConnectionController extends AbstractController
{
    #[Route('/', name: 'app_connection_index', methods: ['GET', 'POST'])]
    public function index(UserInterface $user, Request $request, ConnectionRepository $connectionRepository, UserRepository $userRepository): Response
    {
        $connection = new Connection();
        $form = $this->createForm(ConnectionType::class, $connection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $peer = $userRepository->loadUserByIdentifier($connection->getPeerEmail());
            if($peer != null) {
                $connection->setUser($user);
                $connection->setPeer($peer);
                $connection->setStatus(EnumConnectionType::STATUS_APPROVED);
                $connectionRepository->add($connection);
                return $this->redirectToRoute('app_connection_index', [], Response::HTTP_SEE_OTHER); 
            }
        }

        return $this->renderForm('connection/index.html.twig', [
            'connection' => $connection,
            'form' => $form,
            'connections' => $connectionRepository->findByUser($user),

        ]);
    }

    #[Route('/new', name: 'app_connection_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConnectionRepository $connectionRepository): Response
    {
        $connection = new Connection();
        $form = $this->createForm(ConnectionType::class, $connection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $connectionRepository->add($connection);
            return $this->redirectToRoute('app_connection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('connection/new.html.twig', [
            'connection' => $connection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_connection_show', methods: ['GET'])]
    public function show(Connection $connection): Response
    {
        return $this->render('connection/show.html.twig', [
            'connection' => $connection,
        ]);
    }

    #[Route('/{id}', name: 'app_connection_delete', methods: ['POST'])]
    public function delete(Request $request, Connection $connection, ConnectionRepository $connectionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$connection->getId(), $request->request->get('_token'))) {
            $connectionRepository->remove($connection);
        }

        return $this->redirectToRoute('app_connection_index', [], Response::HTTP_SEE_OTHER);
    }
}
