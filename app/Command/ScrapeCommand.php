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
        if($input->getArgument('format') == 'xml') {
            $text = "<element><item>This</item></element>";
        } else {
            $text = json_encode("Test");
        }
        
        $output->writeln($text);
    }
}