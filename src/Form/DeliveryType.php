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
                'choice_label' => 'title',
                'class'        => Delivery::class,
                'expanded'     => true,
                'multiple'     => false,
                'required'     => false,
                'placeholder' => '',
            ]);
    }


}
