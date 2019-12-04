<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Product;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

class MakeSitemapCommand extends Command
{
    protected static $defaultName = 'make-sitemap';
    private $em;


    protected function configure()
    {
        $this
            ->setDescription('Сдампить всё в sitemap.xml ')
        ;
    }



    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        file_put_contents('public/sitemap.xml', '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  ');

        $this->dumpCategories();
        $this->dumpProducts();
        $this->dumpPages();


        file_put_contents('public/sitemap.xml', '</urlset>', FILE_APPEND);

        $io->success('All DONE');
        return 0;
    }



    private function dumpProducts()
    {
        $repo = $this->em->getRepository(Product::class);
        while (true) {
            $FirstResult = 0;
            $categories = $repo->createQueryBuilder('p')
                ->orderBy('p.id')
                ->setMaxResults(100)
                ->setFirstResult($FirstResult)
                ->getQuery()
                ->getScalarResult();

            var_dump($categories);
            exit;
            if (!$categories) break;

            file_put_contents('public/sitemap.xml','', FILE_APPEND);

            $FirstResult = $FirstResult + 100;
        }

        return $FirstResult;
    }



    private function dumpPages()
    {
        $repo = $this->em->getRepository(Page::class);
        while (true) {
            $FirstResult = 0;
            $categories = $repo->createQueryBuilder('p')
                ->orderBy('p.id')
                ->setMaxResults(100)
                ->setFirstResult($FirstResult)
                ->getQuery()
                ->getScalarResult();

            var_dump($categories);
            exit;
            if (!$categories) break;

            file_put_contents('public/sitemap.xml','', FILE_APPEND);
            $FirstResult = $FirstResult + 100;
        }

        return $FirstResult;
    }



    private function dumpCategories()
    {
        $repo = $this->em->getRepository(Category::class);
        while (true) {
            $FirstResult = 0;
            $categories = $repo->createQueryBuilder('c')
                ->orderBy('c.id')
                ->setMaxResults(100)
                ->setFirstResult($FirstResult)
                ->getQuery()
                ->getScalarResult();

            var_dump($categories);
            exit;
            if (!$categories) break;

            file_put_contents('public/sitemap.xml','', FILE_APPEND);
            $FirstResult = $FirstResult + 100;
        }

        return $FirstResult;
    }

}
