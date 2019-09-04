<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Provider;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Imagine\Image\Point;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportAlegrisCommand extends Command
{
    protected static $defaultName = 'alegris-import';
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
            ->setDescription('Import product from alegris')
            ->addArgument('url', InputArgument::REQUIRED, 'url')
            ->addArgument('category', InputArgument::REQUIRED, 'category')
        ;
    }


    private function Pages(string $url): array
    {
        $res     = $this->client->request('GET', $url);
        $body    = (string) $res->getBody();
        $matches = '';
        $pages   = [$url];
        preg_match_all("#<a class='page-numbers' href='(.*)'>.*<\/a>#Uis", $body, $matches);

        foreach ($matches[1] as $match ):
            $pages[] = $match;
        endforeach;

        $pages = array_unique($pages);

        return $pages;
    }

    private function productsOnPage(string $url) : array
    {
        $res = $this->client->request('GET', $url);
        $body = (string) $res->getBody();
        $matches = '';
        preg_match_all('#<a href="(.*)" class="ci-title-shop-link">#Ui', $body,$matches );

        $products = $matches[1];

        return array_unique($products);

    }


    private function parseProduct(string $url): Product
    {
        $res  = $this->client->request('GET', $url);
        $body = (string) $res->getBody();




        preg_match('#<h1 itemprop="name" class="product_title entry-title ci-title-with-breadcrumb">(.*)</h1>#',$body, $matches);
        $title = trim($matches[1]);


        preg_match('#<span class="woocommerce-Price-amount amount">(.*)&nbsp;#Uis',$body, $matches);

        $price = $matches[1];
        $price = (int) str_replace(',', '', $price);

        $p = $this->em->getRepository(Product::class)->findOneBy(['title' => $title]);
        if($p){
            $p->setPrice($price);
            return $p;
        }

        $product = new Product();
        $product->setTitle($title)
                ->setPrice($price)
                ->setProviderId(Provider::PROVIDER_ALEGRIS);
        ;


        preg_match('#<p>(.*)<\/p>#Uis', $body, $matches);
        if (isset($matches[1])) {
            $desc = trim($matches[1]);
            $product->setDescription($desc);
        }


        $images = [];
        preg_match_all("#<a href=\"(.*)\" itemprop=\"image\" class=\"woocommerce-main-image zoom\"#U", $body, $images);

        foreach ($images[1] as $key => $image):
            $img = file_get_contents($image);

            $filename = md5(rand());
            $i = new Image();

            if (0 === $key) $i->setMain(true);

            $i->setName($filename);
            $i->setExt('jpg');
            $product->addImage($i);

            $dirname = 'public/upload' . DIRECTORY_SEPARATOR . $filename[0] . DIRECTORY_SEPARATOR . $filename[1];
            if(!file_exists($dirname)) mkdir($dirname, 0755, true);

            $image = $this->imagine->load($img);

            $palette = new \Imagine\Image\Palette\RGB();
            $canvas  = $this->imagine->create(
                new Box(450, 450),
                $palette->color('#FFFFFF')
            );

            $image->save($dirname . DIRECTORY_SEPARATOR . $filename . '.jpg', [
                'jpeg_quality' => 100,
            ]);

            $big = $image->thumbnail(new Box(450, 450), ManipulatorInterface::THUMBNAIL_INSET | ManipulatorInterface::THUMBNAIL_FLAG_UPSCALE);

            $y = (int) (450 - $big->getSize()->getHeight()) / 2;
            $x = (int) (450 - $big->getSize()->getWidth()) / 2;


            $canvas
                ->paste($big, new Point($x, $y))
                ->save($dirname . DIRECTORY_SEPARATOR . $filename . Image::IMAGE_THUMB_BIG . '.jpg', [
                    'jpeg_quality' => 100,
                ]);
            ;

            $canvas  = $this->imagine->create(
                new Box(160, 160),
                $palette->color('#FFFFFF')
            );

            $small = $image->thumbnail(new Box(160, 160), ManipulatorInterface::THUMBNAIL_INSET | ManipulatorInterface::THUMBNAIL_FLAG_UPSCALE);
            $y = (int) (160 - $small->getSize()->getHeight()) / 2;
            $x = (int) (160 - $small->getSize()->getWidth()) / 2;

            $canvas
                ->paste($small, new Point($x, $y))
                ->save($dirname . DIRECTORY_SEPARATOR . $filename . Image::IMAGE_THUMB_SMALL . '.jpg', [
                    'jpeg_quality' => 100,
                ]);

        endforeach;


        return $product;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io   = new SymfonyStyle($input, $output);

        $url  = $input->getArgument('url');
        $arg2 = $input->getArgument('category');
        $now = new \DateTime();

        $category = $this->em->getRepository(Category::class)->find($arg2);
        if (!$category) throw new \Exception("categoty id = $arg2 not found!");

        foreach ($this->Pages($url) as $page) {
            foreach ($this->productsOnPage($page) as $product) {
                $p = $this->parseProduct($product);
                $p->addCategory($category);

                $this->em->persist($p);
            }
        }

        $this->em->flush();

        $this->em->getRepository(Product::class)->createQueryBuilder('p')
            ->update('p')
            ->set('p.outOfStock', true)
            ->where('p.createdAt < :now')
            ->andWhere('p.categories IN (:categories)')
            ->andWhere('p.provider_id = :provider_id')
            ->set('now', $now)
            ->set('provider_id', Provider::PROVIDER_ALEGRIS)
            ->set('categories', [$category->getId()])
        ;

        $io->success('ALL DONE!');
    }
}
