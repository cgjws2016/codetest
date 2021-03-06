<?php

namespace SainsBot\Command;

use SainsBot\Scraper;
use SainsBot\Scraper\Strategy\CurlStrategy;
use SainsBot\Scraper\Parser\DomCrawlerParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScrapeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('scrape')
            ->setDescription('Scrape product data from a store URL')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'A listings page to scrape.'
            )
            ->addArgument(
                'format',
                InputArgument::OPTIONAL,
                'Output format'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scraper = new Scraper(new CurlStrategy(), new DomCrawlerParser());

        $scraper->setTarget($input->getArgument('url'));

        $collection = $scraper->scrape();

        // We're only using JSON for now, but there's no reason not to support
        // other formats or output types in the future - method is stubbed but 
        // included as an example.
        if ($input->getArgument('format') == 'xml') {
            $result = $collection->toXml();
        } else {
            $result = $collection->toJson();
        }

        $output->writeln($result);
    }
}
