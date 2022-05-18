<?php

namespace App\Controller;

use App\Entity\Expense;
use App\Entity\Connection;
use App\Entity\Loan;
use App\Form\ExpenseType;
use App\Repository\ExpenseRepository;
use App\Repository\LoanRepository;
use App\Repository\ConnectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/expense')]
class ExpenseController extends AbstractController
{
    #[Route('/', name: 'app_expense_index', methods: ['GET'])]
    public function index(ExpenseRepository $expenseRepository): Response
    {
        return $this->render('expense/index.html.twig', [
            'expenses' => $expenseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_expense_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ExpenseRepository $expenseRepository): Response
    {
        $expense = new Expense();
        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expenseRepository->add($expense);
            return $this->redirectToRoute('app_expense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('expense/new.html.twig', [
            'expense' => $expense,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_expense_show', methods: ['GET', 'POST'])]
    public function show(UserInterface $user, Request $request, Connection $connection, ExpenseRepository $expenseRepository, LoanRepository $loanRepository, ConnectionRepository $connectionRepository): Response
    {
        $peer = $connection->getUser()->equals($user)? $connection->getPeer(): $user;
        $expense = new Expense();
        $user->setDisplayName('You');
        $peer->setDisplayName($peer->getNickname());
        $form = $this->createForm(ExpenseType::class, $expense, array(
            'paidBy' => [ $user, $peer ]
        ));
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // create the expense
            $expense->setPercentShouldered($expense->getPercentShouldered() == null? 50: $expense->getPercentShouldered());
            $expense->setCreatedBy($user);
            $expenseRepository->add($expense);

            // write up the loan
            $borrower = $connection->getUser()->equals($expense->getPaidBy())? $connection->getPeer(): $connection->getUser();
            $loan = new Loan();
            $loan->setLender($expense->getPaidBy());
            $loan->setBorrower($borrower);
            $loan->setAmount($expense->getTotalAmount()*(1-($expense->getPercentShouldered()/100)));
            $loan->setDate($expense->getDate());
            $loan->setCategory($expense->getCategory());
            $loan->setExpense($expense);
            $loanRepository->add($loan);

            // write up personal loan for tracking purposes
            if($loan->getLender()->equals($user)) {
                $personal = new Loan();
                $personal->setLender($user);
                $personal->setBorrower($user);
                $personal->setAmount($expense->getTotalAmount()*($expense->getPercentShouldered()/100));
                $personal->setDate($expense->getDate());
                $personal->setCategory($expense->getCategory());
                $personal->setExpense($expense);
                $loanRepository->add($personal);
            }

            // update connection amounts
            if($user->equals($connection->getUser())) {
                if($loan->getLender()->equals($user)) {
                    $balance = $connection->getUserDebt() - $loan->getAmount();

                    if($balance > 0) {
                        $connection->setUserDebt($balance);
                    } else {
                        $balance = $balance * -1;
                        $connection->setUserDebt(0);
                        $connection->setPeerDebt($balance);
                    }
                } else {
                    $balance = $connection->getPeerDebt() - $loan->getAmount();

                    if($balance > 0) {
                        $connection->setPeerDebt($balance);
                    } else {
                        $balance = $balance * -1;
                        $connection->setPeerDebt(0);
                        $connection->setUserDebt($balance);
                    }
                }
            } else {
                if($loan->getLender()->equals($user)) {
                    $balance = $connection->getPeerDebt() - $loan->getAmount();

                    if($balance > 0) {
                        $connection->setPeerDebt($balance);
                    } else {
                        $balance = $balance * -1;
                        $connection->setPeerDebt(0);
                        $connection->setUserDebt($balance);
                    }
                    
                } else {
                    $balance = $connection->getUserDebt() - $loan->getAmount();

                    if($balance > 0) {
                        $connection->setUserDebt($balance);
                    } else {
                        $balance = $balance * -1;
                        $connection->setUserDebt(0);
                        $connection->setPeerDebt($balance);
                    }
                }
            }

            $connectionRepository->add($connection);

            return $this->redirectToRoute('app_expense_show', ['id' => $connection->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('expense/index.html.twig', [
            'expense' => $expense,
            'form' => $form,
            'connection' => $connection,
            'peer' => $peer,
            'loans' => $loanRepository->findByBorrowerAndLender($user, $peer)
        ]);
    }

    #[Route('/{id}/edit', name: 'app_expense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Expense $expense, ExpenseRepository $expenseRepository): Response
    {
        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expenseRepository->add($expense);
            return $this->redirectToRoute('app_expense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('expense/edit.html.twig', [
            'expense' => $expense,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_expense_delete', methods: ['POST'])]
    public function delete(Request $request, Expense $expense, ExpenseRepository $expenseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$expense->getId(), $request->request->get('_token'))) {
            $expenseRepository->remove($expense);
        }

        return $this->redirectToRoute('app_expense_index', [], Response::HTTP_SEE_OTHER);
    }
}
