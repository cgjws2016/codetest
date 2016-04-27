<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use SainsBot\Command\ScrapeCommand;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    private $application;
    private $tester;

    public function __construct()
    {
        $this->application = new Application();
        $this->application->add(new ScrapeCommand());
    }
    /**
     * @When /^I run "([^"]*)" command$/
     */
    public function iRunCommand($name)
    {
        $command = $this->application->find($name);
        $this->tester = new CommandTester($command);
        $this->tester->execute(array('command' => $command->getName()));
    }

    /**
     * @When I run :arg1 command with arguments:
     */
    public function iRunCommandWithArguments($name, TableNode $table)
    {
        $command = $this->application->find($name);
        $this->tester = new CommandTester($command);

        $args = $table->getHash();
        $options = array('command' => $command->getName());
        foreach($args as $arg) {
            $options[$arg['arg']] = $arg['value'];
        }
        $this->tester->execute($options);
        
    }

    /**
     * @Then /^I should see "([^"]*)"$/
     */
    public function iShouldSee($string)
    {
        expect(trim($this->tester->getDisplay()))->toBe($string);
    }
}

