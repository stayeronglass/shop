<?php

namespace App\Form;

use App\Entity\Delivery;
use App\Entity\Payment;
use App\Entity\My\Address;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartCheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', EntityType::class, [
                'class'        => Address::class,
                'placeholder'  => false,
                'expanded'     => true,
                'multiple'     => false,
                'required'     => true,
                'choice_label' => 'recipient',
                'translation_domain' => false,
                'label' => '',
                'label_attr' => ['style' => 'display:none'],
            ])

            ->add('delivery', EntityType::class, [
                'class'        => Delivery::class,
                'placeholder'  => false,
                'expanded'     => true,
                'multiple'     => false,
                'required'     => true,
                'choice_label' => function (Delivery $delivery) {
                    return $delivery->getTitle() . ' ' . $delivery->getPrice() . '₽';
                },
                'translation_domain' => false,
                'label_attr' => ['style' => 'display:none'],
            ])

            ->add('payment', EntityType::class, [
                'class'        => Payment::class,
                'placeholder'  => false,
                'expanded'     => true,
                'multiple'     => false,
                'required'     => true,
                'choice_label' => function (Payment $payment) {
                    return $payment->getTitle();
                },
                'translation_domain' => false,
                'label_attr' => ['style' => 'display:none'],
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {

    }

}
