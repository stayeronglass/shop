<?php

namespace ShopBundle\Admin;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use ShopBundle\Entity\File;
use ShopBundle\Form\Type\MultipleuploadType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;

class ProductAdmin extends AbstractAdmin
{

    private $container = null;

    public function __construct($code, $class, $baseControllerName, $container)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
    }

    /**
     * @param $object \ShopBundle\Entity\Product
     */
    public function manadgeUploads($object){

        $uploadableManager = $this->container->get('stof_doctrine_extensions.uploadable.manager');
        $pics = $object->getPictures();
        foreach ($pics as $pic) {
            $pic->setProduct($object);
        }
        dump($pics);
        exit;
        $i = 0;
        foreach ( $this->getRequest()->files->all() as $file):

            $uploadableManager->markEntityToUpload($pics[$i], $file['pictures'][1]['file']);

            $i++;
        endforeach;

    }


    public function preUpdate($object){
        $this->manadgeUploads($object);
    }

    public function prePersist($object)
    {
        $this->manadgeUploads($object);
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
            ->add('pictures', 'sonata_type_collection', [
                'required'   => false,
            ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'position',
                    'admin_code' => 'shop.admin.file',
                ])
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('delete')
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
                    /*
                    'photo' => array(
                        'template' => 'ShopBundle:admin:product/list__action_photo.html.twig'
                    )
                    */
                )
            ))

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
