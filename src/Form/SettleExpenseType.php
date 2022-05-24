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

class SettleExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('totalAmount', NumberType::class, [
                'html5' => true,
                'data' => $options['defaultAmount'],
                'attr' => ['max' => $options['defaultAmount'], 'min' => 0, 'step' => '0.01']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expense::class,
            'defaultAmount' => null
        ]);
    }
}
