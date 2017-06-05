<?php
namespace ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkuploadType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required'   => false,
            'data_class' => null,
            'mapped'     => false,
            'attr' => [
                'placeholder' => 'Скопипастить сюда ссылку на фотку',
                'class' => 'link',
            ]
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }

}

