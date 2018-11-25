<?php

namespace App\Form;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('delivery', EntityType::class, [
                'choice_label' => 'title',
                'class' => Product::class,
                'query_builder' => function(ProductRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->andWhere('p.id in(297, 298) ')
                        ->orderBy('p.id', 'ASC');
                },
                'expanded' => true,
                'multiple' => false,
            ]);
    }


}
