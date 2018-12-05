<?php

namespace App\Admin;

use App\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\CollectionType;

class ProductAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class, [

        ]);
        $formMapper->add('description', SimpleFormatterType::class, [
            'format' => 'markdown',
            'ckeditor_context' => 'default', // optional
        ]);
        $formMapper->add('price', TextType::class, [
            'label' => 'Цена',
        ]);

        $formMapper->add('categories', ModelAutocompleteType::class, [
            'property' => 'name',
            'multiple' => true,
        ]);

        $formMapper->add('manufacturers', ModelAutocompleteType::class, [
            'label' => 'Производитель',
            'property' => 'name',
        ]);

        $formMapper->add('material', ModelAutocompleteType::class, [
            'label' => 'Материал',
            'property' => 'name',
        ]);

        $formMapper->add('new', CheckboxType::class, [
            'label' => 'В новинки',
            'required' => false,
        ]);
        $formMapper->add('banner', CheckboxType::class, [
            'label' => 'В банер',
            'required' => false,
        ]);

        $formMapper->add('outOfStock', CheckboxType::class, [
            'label' => 'Нет в наличии',
            'required' => false,
        ]);

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('id')
            ->addIdentifier('title')
        ;
    }
}
