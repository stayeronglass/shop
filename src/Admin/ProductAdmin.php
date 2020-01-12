<?php

namespace App\Admin;

use App\Entity\Image;
use App\Entity\Product;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ProductAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', TextType::class, [
                'required' => true,
                'label'    => 'Заголовок',
            ])
            ->add('description', SimpleFormatterType::class, [
                'required' => true,
                'format'   => 'text',
                'label'    => 'Текст',
            ])
            ->add('price', TextType::class, [
                'required' => true,
                'label'    => 'Цена',
            ])
            ->add('categories', ModelAutocompleteType::class, [
                'label'    => 'Категории',
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
            ])
            ->add('outOfStock', CheckboxType::class, [
                'label'    => 'Нет в наличии',
            ])
        ;
        $help   = '';
        foreach ($this->getSubject()->getImages() as $image):
            $help .= '<img src="'.$image->getWebPath(Image::IMAGE_THUMB_SMALL).'" class="admin-preview" alt="" style="width:100px;heigth:auto;" />';
        endforeach;

        $formMapper
            ->add('images', ModelType::class, [
                'label'    => 'Картинки',
                'multiple' => true,
                'required' => false,
                'help'     => $help,
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


    public function postUpdate($product)
    {
        /**
         * @var $cache TagAwareCacheInterface
         *
         */
        $cache = $this->getConfigurationPool()->getContainer()->get('cache.app');

        $cache->delete('product' . $product->getId());
        $cache->delete('product_' . $product->getId() . '_etag');
    }


    private function manageEmbeddedImageAdmins(Product $product)
    {
        foreach ($product->getImages() as $image):
            $image->setProducts($product);
        endforeach;
    }
}
