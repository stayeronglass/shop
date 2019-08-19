<?php

namespace App\Command;

use App\Entity\Category;
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
    protected static $defaultName = 'import-alegris';
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

    }

    private function productsOnPage(string $url) : array
    {

    }


    private function parseProduct(string $url): Product
    {
        $product = new Product();

        $product->setProviderId(Provider::PROVIDER_ALEGRIS);

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
