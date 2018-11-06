<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('zip', TextType::class, [
                'label' => 'zip',
            ])
            ->add('country', TextType::class, [
                'label' => 'country',
            ])
            ->add('city', TextType::class, [
                'label' => 'city',
            ])
            ->add('bld', TextType::class, [
                'label' => 'bld',
            ])
            ->add('recipient', TextType::class, [
                'label' => 'recipient',
            ])
            ->add('phone', TextType::class, [
                'label' => 'phone',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
