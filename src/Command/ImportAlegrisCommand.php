<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Provider;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Imagine\Gd\Imagine;
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
            ->setDescription('Import product from miniaturesfan')
            ->addArgument('url', InputArgument::REQUIRED, 'url')
            ->addArgument('category', InputArgument::REQUIRED, 'category')
        ;
    }


    private function Pages(string $url): array
    {
        $url = "https://alegris.ru/product-category/g-workshop/%d1%80%d0%b0%d1%81%d0%bf%d1%80%d0%be%d0%b4%d0%b0%d0%b6%d0%b0-games-workshop/page/1/";
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
        $product->setTitle($title);

        $product->setProviderId(Provider::PROVIDER_ALEGRIS);
        preg_match('#<p>(.*)<\/p>#Uis',$body, $matches);

        $desc = trim($matches[1]);
        $product->setDescription($desc);
        $product->setPrice($price);

        $images = [];
        preg_match_all("#'.*/products_pictures/.*enl.*\.jpg'#", $body, $images);

        foreach ($images[0] as $key => $image):
            $i = new Image();
            if (0 === $key) $i->setMain(true);
            $product->addImage($i);

        endforeach;


        return $product;
    }


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
    }
}
