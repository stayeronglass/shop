<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipient', TextType::class, [
                'label' => 'recipient',
                'help' => 'ФИО получателя',
                'attr'  => [
                    'placeholder' => "Иванову Ивану Ивановичу",
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'phone',
                'help' => 'телефон получателя',
                'required' => false,
            ])

            ->add('zip', TextType::class, [
                'label' => 'zip',
                'help' => 'zip',
                'attr'  => [
                    'placeholder' => "127001",
                ],
            ])
            ->add('country', TextType::class, [
                'label' => 'country',
                'help' => 'country',
                'attr'  => [
                    'placeholder' => "Россия",
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'city',
                'help' => 'город',
                'attr'  => [
                    'placeholder' => "Москва",
                ],
            ])
            ->add('bld', TextType::class, [
                'label' => 'bld',
                'help' => 'address',
                'attr'  => [
                    'placeholder' => "улица Ленина дом 5, квартира 1",
                ],
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
