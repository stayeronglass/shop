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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MakeSitemapCommand extends Command
{
    protected static $defaultName = 'make-sitemap';
    private $em;
    private $router;


    protected function configure()
    {
        $this
            ->setDescription('Сдампить всё в sitemap.xml ')
        ;
    }



    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $router)
    {
        parent::__construct();
        $this->router = $router;
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
            $products = $repo->createQueryBuilder('p')

                ->select('p.id, p.updatedAt')
                ->orderBy('p.id')
                ->setMaxResults(100)
                ->setFirstResult($FirstResult)
                ->getQuery()
                ->getArrayResult();

            if (!$products) break;
            foreach ($products as $product):
                $url = "
                    <url>
                          <loc>".$this->router->generate('product_show', ['id' => $product['id']], UrlGeneratorInterface::ABSOLUTE_URL)."</loc>
                          <lastmod>".$product['updatedAt']->format('Y-m-d')."</lastmod>
                          <changefreq>daily</changefreq>
                          <priority>0.8</priority>
                       </url>
                    ";
                file_put_contents('public/sitemap.xml',$url, FILE_APPEND);
            endforeach;


            $FirstResult = $FirstResult + 100;
        }

        return $FirstResult;
    }



    private function dumpPages()
    {
        $repo = $this->em->getRepository(Page::class);
        while (true) {
            $FirstResult = 0;
            $pages = $repo->createQueryBuilder('p')
                ->orderBy('p.id, p.slug, p.updatedAt')
                ->setMaxResults(100)
                ->setFirstResult($FirstResult)
                ->getQuery()
                ->getArrayResult();

            if (!$pages) break;

            foreach ($pages as $page):
                $url = "
                    <url>
                          <loc>".$this->router->generate('page', ['slug' => $page['slug']], UrlGeneratorInterface::ABSOLUTE_URL)."</loc>
                          <lastmod>".$page['updatedAt']->format('Y-m-d')."</lastmod>
                          <changefreq>daily</changefreq>
                          <priority>0.8</priority>
                       </url>
                    ";
                file_put_contents('public/sitemap.xml',$url, FILE_APPEND);
            endforeach;

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
                ->select('c.id,c.slug, c.updatedAt')
                ->orderBy('c.id')
                ->setMaxResults(100)
                ->setFirstResult($FirstResult)
                ->getQuery()
                ->getArrayResult();

            if (!$categories) break;
            foreach ($categories as $category):
                $url = "
                    <url>
                          <loc>".$this->router->generate('category', ['slug' => $category['slug']], UrlGeneratorInterface::ABSOLUTE_URL)."</loc>
                          <lastmod>".$category['updatedAt']->format('Y-m-d')."</lastmod>
                          <changefreq>daily</changefreq>
                          <priority>0.8</priority>
                       </url>
                    ";
                file_put_contents('public/sitemap.xml',$url, FILE_APPEND);
                $FirstResult = $FirstResult + 100;
           endforeach;

        }

        return $FirstResult;
    }

}
