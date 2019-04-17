<?php

namespace App\Admin;

use App\Entity\Product;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Sonata\FormatterBundle\Form\Type\FormatterType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\AdminType;

class ProductAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class, [

        ])
            ->add('description', SimpleFormatterType::class, [
                'format' => 'text',
            ])
            ->add('price', TextType::class, [
                'label' => 'Цена',
            ])
            ->add('categories', ModelAutocompleteType::class, [
                'property' => 'name',
                'multiple' => true,
            ])
            ->add('categories', ModelAutocompleteType::class, [
                'property' => 'name',
                'multiple' => true,
            ])
            ->add('manufacturers', ModelAutocompleteType::class, [
                'label'    => 'Производитель',
                'property' => 'name',
            ])
            ->add('material', ModelAutocompleteType::class, [
                'label'    => 'Материал',
                'property' => 'name',
            ])
            ->add('new', CheckboxType::class, [
                'label'    => 'В новинки',
                'required' => false,
            ])
            ->add('banner', CheckboxType::class, [
                'label'    => 'В банер',
                'required' => false,
            ])
            ->add('outOfStock', CheckboxType::class, [
                'label'    => 'Нет в наличии',
                'required' => false,
            ])
            ->add('images', ModelType::class, [
                'label'    => 'Картинки',
                'multiple' => true,
                'required' => true,
            ])

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('id')
            ->addIdentifier('title')
        ;
    }


    public function prePersist($product)
    {
        $this->manageEmbeddedImageAdmins($product);
    }


    public function preUpdate($product)
    {
        $this->manageEmbeddedImageAdmins($product);
    }


    private function manageEmbeddedImageAdmins(Product $product)
    {
        foreach ($product->getImages() as $image):
            $image->setProducts($product);
        endforeach;
    }
}
