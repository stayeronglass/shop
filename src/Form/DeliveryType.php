<?php

namespace App\Form;

use App\Entity\Delivery;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'label' => false,
                'choice_label' => function() {
                    return false;
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Delivery::class,
        ]);
    }

}
