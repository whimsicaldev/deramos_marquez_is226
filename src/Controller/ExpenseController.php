<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Expense;
use App\Entity\Connection;
use App\Entity\Loan;
use App\Form\ExpenseType;
use App\Form\SettleExpenseType;
use App\Form\PersonalExpenseType;
use App\Repository\ExpenseRepository;
use App\Repository\CategoryRepository;
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
    #[Route('/', name: 'app_expense_index', methods: ['GET', 'POST'])]
    public function index(Request $request, UserInterface $user, LoanRepository $loanRepository, ExpenseRepository $expenseRepository): Response
    {
        $expense = new Expense();
        $form = $this->createForm(PersonalExpenseType::class, $expense);
        $form->handleRequest($request);
        $toastMessage = $request->get('toastMessage')? $request->get('toastMessage'): false;
        $toastType = $request->get('toastType')? $request->get('toastType'): '';

        if ($form->isSubmitted() && $form->isValid()) {
            // create the expense
            $expense->setCreatedBy($user);
            $expense->setIsPersonal(true);
            $expenseRepository->add($expense);

            $loan = new Loan();
            $loan->setLender($user);
            $loan->setBorrower($user);
            $loan->setAmount($expense->getTotalAmount());
            $loan->setDate($expense->getDate());
            $loan->setCategory($expense->getCategory());
            $loan->setExpense($expense);
            $loanRepository->add($loan);
            
            return $this->redirectToRoute('app_expense_index', ['toastMessage' => 'Personal expense created.', 'toastType' => 'success'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('expense/index.html.twig', [
            'loans' => $loanRepository->findByBorrower($user),
            'expense' => $expense,
            'form' => $form,
            'toastMessage' => $toastMessage,
            'toastType' => $toastType
        ]);
    }

    #[Route('/{id}', name: 'app_expense_show', methods: ['GET', 'POST'])]
    public function show(UserInterface $user, Request $request, Connection $connection, ExpenseRepository $expenseRepository, 
    LoanRepository $loanRepository, ConnectionRepository $connectionRepository, CategoryRepository $categoryRepository): Response
    {
        $toastMessage = $request->get('toastMessage')? $request->get('toastMessage'): false;
        $toastType = $request->get('toastType')? $request->get('toastType'): '';
        $peer = $connection->getUser()->equals($user)? $connection->getPeer(): $connection->getUser();
        $expense = new Expense();
        $user->setDisplayName('You');
        $peer->setDisplayName($peer->getNickname());
        $form = $this->createForm(ExpenseType::class, $expense, array(
            'paidBy' => [ $user, $peer ]
        ));
        $form->handleRequest($request);

        $settleForm = $this->createForm(SettleExpenseType::class, $expense, array(
            'defaultAmount' => $connection->getUserDebt() > 0? $connection->getUserDebt(): $connection->getPeerDebt()
        ));
        $settleForm->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $expense->setCreatedBy($user);
            $expense->setIsPersonal(false);
            $expenseRepository->add($expense);
            $this->createLoans($expense, $connectionRepository, $connection, $loanRepository, $user);
            return $this->redirectToRoute('app_expense_show', ['id' => $connection->getId(), 'toastMessage' => 'Shared expense created.', 'toastType' => 'success'], Response::HTTP_SEE_OTHER);
        } else if($settleForm->isSubmitted()) {
            $expense->setCreatedBy($user);
            $expense->setPercentShouldered(0);
            $expense->setName('Settlement');
            $expense->setIsPersonal(false);
            $expense->setPaidBy($connection->getUserDebt() > 0? $connection->getUser(): $connection->getPeer());
            $expense->setDate(new \DateTime());
            $expense->setCategory($categoryRepository->loadByName('Settlement'));
            $expenseRepository->add($expense);
            $this->createLoans($expense, $connectionRepository, $connection, $loanRepository, $user);
            return $this->redirectToRoute('app_expense_show', ['id' => $connection->getId(), 'toastMessage' => 'Balances has been settled.', 'toastType' => 'success'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('expense/connection-expense.html.twig', [
            'expense' => $expense,
            'form' => $form,
            'connection' => $connection,
            'peer' => $peer,
            'loans' => $loanRepository->findByBorrowerAndLender($user, $peer),
            'toastMessage' => $toastMessage,
            'toastType' => $toastType,
            'settleForm' => $settleForm,
            'expense' => $expense
        ]);
    }

    #[Route('/{id}/edit', name: 'app_expense_edit', methods: ['GET', 'POST'])]
    public function edit(UserInterface $user, Request $request, Expense $expense, ExpenseRepository $expenseRepository, ConnectionRepository $connectionRepository, LoanRepository $loanRepository): Response
    {
        $toastMessage = $request->get('toastMessage')? $request->get('toastMessage'): false;
        $toastType = $request->get('toastType')? $request->get('toastType'): '';
        $users = new ArrayCollection();
        foreach($expense->getLoans() as $loan) {
            if($loan->getBorrower()->equals($user)) {
                $loan->getBorrower()->setDisplayName('You');
            } else {
                $loan->getBorrower()->setDisplayName($loan->getBorrower()->getNickname());
            }
            $users[] = $loan->getBorrower();
        }

        $isPersonal = $expense->getIsPersonal();

        
        $form = $isPersonal? $this->createForm(PersonalExpenseType::class, $expense):
            $this->createForm(ExpenseType::class, $expense, array(
                'paidBy' => $users
            ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->deleteLoans($expense, $connectionRepository, $loanRepository);
            $connection = null;

            if(!$isPersonal) {
                $connection = $connectionRepository->findByUserAndPeer($users[0], $users[1]);
            }
            
            $this->createLoans($expense, $connectionRepository, $connection, $loanRepository, $user);
            $expenseRepository->add($expense);

            if($isPersonal) {
                return $this->redirectToRoute('app_expense_index', ['toastMessage' => 'Personal expense updated.', 'toastType' => 'update'], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_expense_show', ['id' => $connection->getId(), 'toastMessage' => 'Shared expense updated.', 'toastType' => 'update'], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('expense/edit.html.twig', [
            'expense' => $expense,
            'form' => $form,
            'isPersonal' => $isPersonal,
            'toastMessage' => $toastMessage,
            'toastType' => $toastType
        ]);
    }

    #[Route('/{id}/delete', name: 'app_expense_delete', methods: ['POST'])]
    public function delete(Expense $expense, ExpenseRepository $expenseRepository, ConnectionRepository $connectionRepository, LoanRepository $loanRepository): Response
    {
        $connection = $this->deleteLoans($expense, $connectionRepository, $loanRepository);
        $expense->setCreatedBy(null);
        $expense->setCategory(null);
        $expenseRepository->remove($expense);

        if($expense->getIsPersonal()) {
            return $this->redirectToRoute('app_expense_index', ['toastMessage' => 'Personal expense deleted.', 'toastType' => 'warning'], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('app_expense_show', ['id' => $connection->getId(), 'toastMessage' => 'Shared expense deleted.', 'toastType' => 'warning'], Response::HTTP_SEE_OTHER);
        }
    }

    private function deleteLoans(Expense $expense, ConnectionRepository $connectionRepository, LoanRepository $loanRepository): ?Connection
    {
        $connection = null;
        foreach($expense->getLoans() as $loan) {
            if(!$loan->getLender()->equals($loan->getBorrower())) {
                $connection = $connectionRepository->findByUserAndPeer($loan->getLender(), $loan->getBorrower());

                // get user and peer
                $user = $connection->getUser();
                $peer = $connection->getPeer();
                $user->setIsBorrower($user->equals($loan->getBorrower()));

                if($user->getIsBorrower()) {
                    $connection->setUserDebt($connection->getUserDebt()-$loan->getAmount());
                    if($connection->getUserDebt() < 0) {
                        $connection->setPeerDebt($connection->getPeerDebt() + (-1 * $connection->getUserDebt()));
                        $connection->setUserDebt(0);
                    }
                } else {
                    $connection->setPeerDebt($connection->getPeerDebt()-$loan->getAmount());
                    if($connection->getPeerDebt() < 0) {
                        $connection->setUserDebt($connection->getUserDebt() + (-1 * $connection->getPeerDebt()));
                        $connection->setPeerDebt(0);
                    }
                }

                $connectionRepository->add($connection);
            }

            $loan->setLender(null);
            $loan->setBorrower(null);
            $loan->setExpense(null);
            $loan->setCategory(null);
            $loanRepository->remove($loan);
        }

        return $connection;
    }

    private function createLoans(Expense $expense, ConnectionRepository $connectionRepository, Connection $connection = null, LoanRepository $loanRepository, UserInterface $user) {
        $expense->setPercentShouldered($expense->getPercentShouldered() == null? 50: $expense->getPercentShouldered());
        
        // create loan for connection
        if($connection != null) {
            $borrower = $connection->getUser()->equals($expense->getPaidBy())? $connection->getPeer(): $connection->getUser();
            $loan = new Loan();
            $loan->setLender($expense->getPaidBy());
            $loan->setBorrower($borrower);
            $loan->setAmount($expense->getTotalAmount()*(1-($expense->getPercentShouldered()/100)));
            $loan->setDate($expense->getDate());
            $loan->setCategory($expense->getCategory());
            $loan->setExpense($expense);
            $loanRepository->add($loan);

            $connUser = $connection->getUser();
            $peer = $connection->getPeer();
            $connUser->setIsBorrower($connUser->equals($loan->getBorrower()));

            if(!$connUser->getIsBorrower()) {
                $connection->setUserDebt($connection->getUserDebt()-$loan->getAmount());
                if($connection->getUserDebt() < 0) {
                    $connection->setPeerDebt($connection->getPeerDebt() + (-1 * $connection->getUserDebt()));
                    $connection->setUserDebt(0);
                }
            } else {
                $connection->setPeerDebt($connection->getPeerDebt()-$loan->getAmount());
                if($connection->getPeerDebt() < 0) {
                    $connection->setUserDebt($connection->getUserDebt() + (-1 * $connection->getPeerDebt()));
                    $connection->setPeerDebt(0);
                }
            }

            $connectionRepository->add($connection);
        }
        

        // write up personal loan for tracking purposes
        $personal = new Loan();
        $personal->setLender($expense->getPaidBy());
        $personal->setBorrower($expense->getPaidBy());
        $personal->setAmount($expense->getTotalAmount()*($expense->getPercentShouldered()/100));
        $personal->setDate($expense->getDate());
        $personal->setCategory($expense->getCategory());
        $personal->setExpense($expense);
        $loanRepository->add($personal);
    }
}
