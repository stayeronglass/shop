<?php

namespace App\Admin;

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
                'label' => 'Производитель',
                'property' => 'name',
            ])
            ->add('material', ModelAutocompleteType::class, [
                'label' => 'Материал',
                'property' => 'name',
            ])
            ->add('new', CheckboxType::class, [
                'label' => 'В новинки',
                'required' => false,
            ])
            ->add('banner', CheckboxType::class, [
                'label' => 'В банер',
                'required' => false,
            ])
            ->add('outOfStock', CheckboxType::class, [
                'label' => 'Нет в наличии',
                'required' => false,
            ])
            ->add('images', ModelType::class, [
                'multiple' => true,
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


    public function prePersist($page)
    {
        $this->manageEmbeddedImageAdmins($page);
    }


    public function preUpdate($page)
    {
        $this->manageEmbeddedImageAdmins($page);
    }


    private function manageEmbeddedImageAdmins($page)
    {
        // Cycle through each field
        foreach ($this->getFormFieldDescriptions() as $fieldName => $fieldDescription) {
            // detect embedded Admins that manage Images
            if ($fieldDescription->getType() === 'sonata_type_admin' &&
                ($associationMapping = $fieldDescription->getAssociationMapping()) &&
                $associationMapping['targetEntity'] === 'App\Entity\Image'
            ) {
                $getter = 'get'.$fieldName;
                $setter = 'set'.$fieldName;

                /** @var Image $image */
                $image = $page->$getter();

                if ($image) {
                    if ($image->getFile()) {
                        // update the Image to trigger file management
                        $image->refreshUpdated();
                    } elseif (!$image->getFile() && !$image->getFilename()) {
                        // prevent Sf/Sonata trying to create and persist an empty Image
                        $page->$setter(null);
                    }
                }
            }
        }
    }
}
