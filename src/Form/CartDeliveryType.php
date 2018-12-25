<?php

namespace App\Form;

use App\Entity\Delivery;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartDeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('delivery', EntityType::class, [
                'class'        => Delivery::class,
                'placeholder'  => false,
                'expanded'     => true,
                'multiple'     => false,
                'required'     => false,
                'choice_label' => function (Delivery $delivery) {
                    return $delivery->getTitle() . ' ' . $delivery->getPrice() . '₽';
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

}
