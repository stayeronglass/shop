<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\Image\ManipulatorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use GuzzleHttp\Client;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class MinifanImportCommand extends Command
{
    protected static $defaultName = 'minifan-import';

    private $client;
    private $em;
    private $imagine;

    public function __construct(EntityManagerInterface $entityManager, ?string $name = null)
    {
        parent::__construct($name);

        $this->client  = new Client();
        $this->em      = $entityManager;
        $this->imagine = new Imagine();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import product from miniaturesfan')
            ->addArgument('url', InputArgument::REQUIRED, 'url')
            ->addArgument('category', InputArgument::REQUIRED, 'category')
        ;
    }

    private function productsOnPage($url){
        $res = $this->client->request('GET', $url);
        $body = (string) $res->getBody();
        $products = [];
        $matches = '';
        preg_match_all('#<a  href=\'/product/(.*)\'>#sU', $body,$matches );

        foreach ($matches[1] as $match):
            $products[] = "http://www.miniaturesfan.ru/product/$match";
        endforeach;

        return array_unique($products);
    }

    private function Pages($url){
        $res     = $this->client->request('GET', $url);
        $body    = (string) $res->getBody();
        $matches = '';
        $pages = [$url];
        preg_match_all('#<a class=no_underline href\="(.*)">#U', $body, $matches);

        foreach ($matches[1] as $match ):
            if (preg_match('#all#', $match)) continue;
            $pages[] = "http://www.miniaturesfan.ru$match";
        endforeach;

        $pages = array_unique($pages);

        return $pages;
    }

    private function parseProduct($url){
        $res = $this->client->request('GET', $url);
        $body = $res->getBody();

        $product = new Product();
        $matches = '';
        preg_match('#<h1>(.*)</h1>#',$body, $matches);

        $title = trim($matches[1]);
        $product->setTitle($title);

        preg_match('#<div class="cpt_product_description"><div>(.*)</div></div>#s',$body, $matches);
        $desc = $matches[1];
        $product->setDescription($desc);

        preg_match('#<span class="totalPrice">(.*) руб.</span>#s',$body, $matches);
        $price = (int) $matches[1];
        $product->setPrice($price);

        $images = [];
        preg_match_all("#'.*/products_pictures/.*enl.*\.jpg'#", $body, $images);

        foreach ($images[0] as $key => $image):

            $image = trim($image, "'");
            $img = file_get_contents("http://www.miniaturesfan.ru$image");

            $filename = md5(rand());
            $i = new Image();

            if (0 === $key) $i->setMain(true);

            $i->setName($filename);
            $i->setExt('jpg');
            $product->addImage($i);

            $dirname = 'public/upload' . DIRECTORY_SEPARATOR . $filename[0] . DIRECTORY_SEPARATOR . $filename[1];
            if(!file_exists($dirname)) mkdir($dirname, 0755, true);

            $image = $this->imagine->load($img);

            $image->save($dirname . DIRECTORY_SEPARATOR . $filename . '.jpg', [
                'jpeg_quality' => 100,
            ]);

            $image->thumbnail(new Box(450, 450), ManipulatorInterface::THUMBNAIL_INSET | ManipulatorInterface::THUMBNAIL_FLAG_UPSCALE)
                ->save($dirname . DIRECTORY_SEPARATOR . $filename . Image::IMAGE_THUMB_BIG . '.jpg', [
                    'jpeg_quality' => 100,
                ]);

            $image->thumbnail(new Box(160, 160), ManipulatorInterface::THUMBNAIL_INSET | ManipulatorInterface::THUMBNAIL_FLAG_UPSCALE)
                ->save($dirname . DIRECTORY_SEPARATOR . $filename . Image::IMAGE_THUMB_SMALL . '.jpg', [
                    'jpeg_quality' => 100,
                ]);

        endforeach;

        if (preg_match('#Нет в наличии #', $body)) $product->setOutOfStock(true);

        return $product;

    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io   = new SymfonyStyle($input, $output);

        $url  = $input->getArgument('url');
        $arg2 = $input->getArgument('category');

        $category = $this->em->getRepository(Category::class)->find($arg2);

        foreach ($this->Pages($url) as $page) {
            foreach ($this->productsOnPage($page) as $product) {
                $p = $this->parseProduct($product);
                $p->addCategory($category);

                $this->em->persist($p);
            }
        }

        $this->em->flush();

        $io->success('ALL DONE!');

    }//protected function execute(InputInterface $input, OutputInterface $output)
}
