<?php

namespace ShopBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProductAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('deletedAt')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('createdAt' , 'datetime', [
                'format' => 'd.m.Y H:i',
            ])
            ->add('updatedAt', 'datetime', [
                'format' => 'd.m.Y H:i',
            ])
            ->add('tags')

            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))

        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $fileFieldOptions = [];

        $formMapper
            ->add('name')
            ->add('description')
            ->add('tags')
            ->add('pictures', 'sonata_type_collection', array(
                'label' => 'Documentos',
                'type_options' => array('delete' => true)), array(
                'edit' => 'inline', 'inline' => 'table', 'sortable' => 'position')
            )
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('createdAt' , 'datetime', [
                'format' => 'd.m.Y H:i',
            ])
            ->add('updatedAt', 'datetime', [
                'format' => 'd.m.Y H:i',
            ])
            ->add('deletedAt', 'datetime', [
                'format' => 'd.m.Y H:i',
            ])
        ;
    }
}
