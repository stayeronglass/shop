<?php

namespace ShopBundle\Admin;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use ShopBundle\Entity\File;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;

class ProductAdmin extends AbstractAdmin
{

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('delete')
            ->add('step1', $this->getRouterIdParameter().'/step1')
            ->add('step2', $this->getRouterIdParameter().'/step2')
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')

            ->add('createdAt', 'doctrine_orm_date_range', [], 'sonata_type_date_range_picker',
                [ 'field_options_start' => [
                    'format' => 'dd.MM.yyyy',
                    'label'  => 'От',
                ],
                    'field_options_end' => [
                        'format' => 'dd.MM.yyyy',
                        'label'  => 'До',
                    ]
                ])
            ->add('updatedAt', 'doctrine_orm_date_range', [], 'sonata_type_date_range_picker',
                [ 'field_options_start' => [
                    'format' => 'dd.MM.yyyy',
                    'label'  => 'От',
                ],
                    'field_options_end' => [
                        'format' => 'dd.MM.yyyy',
                        'label'  => 'До',
                    ]
                ])
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
            ->add('createdAt', 'datetime', [
                'format' => 'd.m.Y H:i',
            ])
            ->add('updatedAt', 'datetime', [
                'format' => 'd.m.Y H:i',
            ])
            ->add('tags')

            ->add('_action', null, array(
                'actions'  => array(
                    'show' => array(),
                    'edit' => array(),
                )
            ))

        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('description' , CKEditorType::class, [
            ])
            ->add('tags')
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
