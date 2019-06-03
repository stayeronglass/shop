<?php

namespace App\Admin;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('parent')
        ;

        $help   = '';
        $images = $this->getSubject()->getImages();

        foreach ($images as $image):
            $help .= '<img src="'.$image->getWebPath(Image::IMAGE_THUMB_SMALL).'" class="admin-preview" alt="" style="width:100px;heigth:auto;" />';
        endforeach;

        $formMapper
            ->add('image', ModelType::class, [
                'label'    => 'Картинка',
                'multiple' => false,
                'required' => true,
                'data'     => null,
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('name')
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


    private function manageEmbeddedImageAdmins(Category $category)
    {

    }
}
