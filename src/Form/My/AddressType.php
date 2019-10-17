<?php

namespace App\Form\My;

use App\Entity\My\Address;
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
                'help' => 'например: Иванову Ивану Ивановичу',
                'attr'  => [
                    'placeholder' => "",
                ],
            ])

            ->add('address', TextType::class, [
                'label' => 'address',
                'help' => 'например: Россия, Москва, ул. Ленина, дом 5, кв. 15',
                'attr'  => [
                ],
            ])

            ->add('zip', TextType::class, [
                'label' => 'zip',
                'help' => 'например: 127001',
                'attr'  => [
                    'placeholder' => "",
                ],
            ])

            ->add('phone', TelType::class, [
                'label' => 'phone',
                'help' => 'например: +70000010',
                'required' => false,
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
