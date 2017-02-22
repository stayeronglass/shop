<?php
namespace ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MultipleuploadType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'data_class' => null,
            'mapped'     => false,
        ]);
    }

    public function getParent()
    {
        return FileType::class;
    }

}

