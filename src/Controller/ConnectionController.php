<?php

namespace App\Controller;

use App\Entity\Connection;
use App\Form\ConnectionType;
use App\Repository\ConnectionRepository;
use App\Repository\LoanRepository;
use App\Repository\ExpenseRepository;
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
        $toastMessage = $request->get('toastMessage')? $request->get('toastMessage'): false;
        $toastType = $request->get('toastType')? $request->get('toastType'): '';

        if ($form->isSubmitted() && $form->isValid()) {
            $peer = $userRepository->loadUserByIdentifier($connection->getPeerEmail());
            if($peer != null && !$peer->equals($user)) {
                $connection = $connectionRepository->findByUserAndPeer($user, $peer);
                if($connection == null) {
                    $connection = new Connection();
                    $connection->setUser($user);
                    $connection->setPeer($peer);
                    $connection->setStatus(EnumConnectionType::STATUS_APPROVED);
                    $connectionRepository->add($connection);
                    return $this->redirectToRoute('app_connection_index', ['toastMessage' => 'Contact has been added.', 'toastType' => 'success'], Response::HTTP_SEE_OTHER);     
                } else {
                    return $this->redirectToRoute('app_connection_index', ['toastMessage' => 'Contact already exists.', 'toastType' => 'warning'], Response::HTTP_SEE_OTHER); 
                }
            } else {
                return $this->redirectToRoute('app_connection_index', ['toastMessage' => 'User does not exists.', 'toastType' => 'warning'], Response::HTTP_SEE_OTHER); 
            }
        }

        return $this->renderForm('connection/index.html.twig', [
            'connection' => $connection,
            'form' => $form,
            'connections' => $connectionRepository->findByUser($user),
            'toastMessage' => $toastMessage,
            'toastType' => $toastType
        ]);
    }

    #[Route('/{id}/delete', name: 'app_connection_delete', methods: ['POST', 'GET'])]
    public function delete(Connection $connection, ConnectionRepository $connectionRepository, LoanRepository $loanRepository, ExpenseRepository $expenseRepository): Response
    {
        $connectionRepository->remove($connection);
        $loans = $loanRepository->findByBorrowerAndLender($connection->getUser(), $connection->getPeer());
        foreach($loans as $loan) {
            $expenseRepository->remove($loan->getExpense());
        }
        return $this->redirectToRoute('app_connection_index', ['toastMessage' => 'Contact has been deleted.', 'toastType' => 'warning'], Response::HTTP_SEE_OTHER);
    }
}
