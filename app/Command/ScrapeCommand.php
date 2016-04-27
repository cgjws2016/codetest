<?php
namespace SainsBot\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ScrapeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('scrape')
            ->setDescription('Greet someone')
            ->addArgument(
                'url',
                InputArgument::OPTIONAL,
                'A listings page to scrape.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}