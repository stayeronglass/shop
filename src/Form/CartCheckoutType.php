<?php

namespace App\Form;

use App\Entity\Cart;
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
                'expanded'     => true,
                'multiple'     => false,
                'required'     => true,
                'choice_label' => 'recipient',
                'label_attr'   => ['style' => 'display:none'],
                'placeholder'  => false,
            ])

            ->add('delivery', EntityType::class, [
                'class'        => Delivery::class,
                'expanded'     => true,
                'multiple'     => false,
                'required'     => true,
                'choice_label' => function (Delivery $delivery) {
                    return $delivery->getTitle() . ' ' . $delivery->getPrice() . '₽';
                },
                'label_attr'   => ['style' => 'display:none'],
                'placeholder'  => false,
            ])

            ->add('payment', EntityType::class, [
                'class'        => Payment::class,
                'expanded'     => true,
                'multiple'     => false,
                'required'     => true,
                'choice_label' => function (Payment $payment) {
                    return $payment->getTitle();
                },
                'label_attr'   => ['style' => 'display:none'],
                'placeholder'  => false,
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => false,
        ]);
    }

}
