<?php
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Form\Type\ModelType;

class OrderAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('id', TextType::class, [

        ])
        ->add('orderStatus', ModelType::class, [
            'label'    => 'Статус',
            'property' => 'title',
            'attr'      => [
                'style' => 'width: 200px'
            ],
        ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('id')
            ->addIdentifier('createdAt')
            ->addIdentifier('updatedAt')
            ->addIdentifier('orderStatus')
        ;
    }

}