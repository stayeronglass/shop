<?php

namespace App\Form;

use App\Entity\Delivery;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DeliveryType extends AbstractType
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
                'label' => '',
                'choice_label' => function() {
                    return '';
                },
            ]);
    }


}
