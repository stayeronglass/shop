<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeSitemapCommand extends Command
{
    protected static $defaultName = 'make-sitemap';

    protected function configure()
    {
        $this
            ->setDescription('Сдампить всё в sitemap.xml ')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        file_put_contents('public/sitemap.xml', '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  ');

        file_put_contents('public/sitemap.xml', '</urlset>', FILE_APPEND);

        $io->success('All DONE');
        return 0;
    }
}
