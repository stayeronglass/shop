<?php
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class KeyValueAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('key');
    }

    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('key')
            ->add('description')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('key', TextType::class, [
                'required' => true
            ])
            ->add('description', TextType::class, [
                'required' => true
            ])
            ->add('value', TextareaType::class, [
                'required' => true
            ])
        ;
    }
}