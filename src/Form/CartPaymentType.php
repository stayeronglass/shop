<?php

namespace App\Form;

use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CartPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('payment', EntityType::class, [
                'class'        => Payment::class,
                'placeholder'  => false,
                'expanded'     => true,
                'multiple'     => false,
                'required'     => false,
                'choice_label' => function (Payment $payment) {
                    return $payment->getTitle();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
}
