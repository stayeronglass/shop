<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Imagine\Gd\Imagine;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportVKCommand extends Command
{
    private $client;
    private $em;
    private $imagine;
    protected static $defaultName = 'import-vk';

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
            ->setDescription('Import product from vk')
            ->addArgument('url', InputArgument::REQUIRED, 'url')
            ->addArgument('category', InputArgument::REQUIRED, 'category')
        ;
    }


    private function productsOnPage($url)
    {
        $res = $this->client->request('GET', $url);
        $body = (string) $res->getBody();
        $products = [];
        $matches = '';
        preg_match_all('#<div class="market_row_name"><a href="(.*)" .*</a></div>#sU', $body,$matches );

        foreach ($matches[1] as $match):
            $products[] = "https://vk.com/$match";
        endforeach;

        return array_unique($products);
    }


    private function parseProduct($url)
    {
        $res  = $this->client->request('GET', $url);
        $body = (string) $res->getBody();

        var_dump($url);
        file_put_contents('111.html', $body);


        $matches = '';
        preg_match('#<div class="market_item_title"#sU',$body, $matches);
        var_dump($matches);exit;
        if (!isset($matches[1])) throw  new \Exception('Чет название не найдено!');
        $title = trim($matches[1]);


        $p =  $this->em->getRepository(Product::class)->findOneBy(['title' => $title]);
        preg_match('#<div class="market_item_title" title=".*>(.*)</div>',$body, $matches);
        if (!isset($matches[1])) throw  new \Exception('Чет цена не найдена!');
        $price = (int) $matches[1];

        $out = false;


        if ($p) {
            $p->setPrice($price)
                ->setOutOfStock($out);

            return $p;
        }
    }


// https://vk.com/
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');
        $category = $input->getArgument('category');

        $category = $this->em->getRepository(Category::class)->find($category);
        //if (!$category) throw new \Exception("category id = $category not found!");

            foreach ($this->productsOnPage($url) as $product) {
                $p = $this->parseProduct($product);
                $p->addCategory($category);

                $this->em->persist($p);
            }

        $this->em->flush();


        $io->success('ALL DONE!');
    }
}
