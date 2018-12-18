<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartAddressType extends AbstractType
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

}
