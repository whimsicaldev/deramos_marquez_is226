<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Expense;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('paidBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'displayName',
                'choice_value' => 'id',
                'choices' => $options['paidBy']
            ])
            ->add('percentShouldered')
            ->add('description')
            ->add('totalAmount', NumberType::class, [
                'html5' => true
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime()
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'choice_value' => 'id'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expense::class,
            'paidBy' => null
        ]);
    }
}
