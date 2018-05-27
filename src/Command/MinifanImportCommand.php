<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MinifanImportCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'minifan-import';

    private $client;
    private $em;

    public function __construct(?string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);

        $this->client = new \GuzzleHttp\Client();
        $this->em = $entityManager;

    }



    protected function configure()
    {
        $this
            ->setDescription('Import product from miniaturesfan')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }



    }


    private function parseCategory($url)
    {
        do {
            foreach ($this->getProducts($url) as $product):
                $this->parseProduct($product);
            endforeach;

            $url = $this->nextPage();
        } while (!empty($url));
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function parseProduct($url)
    {
        $res = $this->client->request('GET', 'http://www.miniaturesfan.ru/product/ardboys/');
        $body = $res->getBody();

        $matches = '';
        preg_match('#<h1>(.*)</h1>#',$body, $matches);
        preg_match('#<div class="cpt_product_description"><div>(.*)</div></div>#s',$body, $matches);


        preg_match_all("#'.*/products_pictures/.*enl.*\.jpg'#", $body, $matches);
        var_dump($matches);
        exit('111');

    }
}
