<?php

use Behat\Behat\Tester\Exception\PendingException;
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
    private $exception;

    public function __construct()
    {
        $this->application = new Application();
        $this->application->add(new ScrapeCommand());
        $this->exception = FALSE;
    }
    /**
     * @When /^I run "([^"]*)" command$/
     */
    public function iRunCommand($name)
    {
        $this->exception = FALSE;
        $command = $this->application->find($name);
        $this->tester = new CommandTester($command);

        try {
            $this->tester->execute(array('command' => $command->getName()));
        } catch(Exception $e) {
            $this->exception = $e;
        }
    }

    /**
     * @When I run :arg1 command with arguments:
     */
    public function iRunCommandWithArguments($name, TableNode $table)
    {
        $this->exception = FALSE;

        $command = $this->application->find($name);
        $this->tester = new CommandTester($command);

        $args = $table->getHash();
        $options = array('command' => $command->getName());
        foreach($args as $arg) {
            $options[$arg['arg']] = $arg['value'];
        }

        try {
            $this->tester->execute($options);
        } catch(Exception $e) {
            $this->exception = $e;
        }
        
    }

    /**
     * @Then /^I should see "([^"]*)"$/
     */
    public function iShouldSee($string)
    {
        $output = $this->tester->getDisplay();
        $contains = strpos($output, $string);
        
        expect(trim($this->tester->getDisplay()))->toBe($string);
    }

    /**
     * @Then I should receive json
     */
    public function iShouldReceiveJson()
    {
        $output = $this->tester->getDisplay();
        json_decode($output);
        expect(json_last_error() == JSON_ERROR_NONE)->toBe(TRUE);
        expect(strlen($output) > 0)->toBe(TRUE);
    }

    /**
     * @Then I should receive xml
     */
    public function iShouldReceiveXml()
    {
        $output = $this->tester->getDisplay();

        libxml_use_internal_errors(true);
        $sxe = simplexml_load_string($output); 
        $xml_invalid = ($sxe === FALSE);
        expect($xml_invalid)->toBe(FALSE);
    }



    /**
     * @Then I should receive exception of class :arg1
     */
    public function iShouldReceiveExceptionOfClass($arg1)
    {
        expect(get_class($this->exception))->toBe($arg1);
    }

    /**
     * @Then I should see products json
     */
    public function iShouldSeeProductsJson()
    {
        $output = $this->tester->getDisplay();
        $output = json_decode($output, true);

        $product_one = array(
            "title" => "Sainsbury's Apricot Ripe & Ready x5",
            "size" => "38.27KB",
            "unit_price" => (float)3.50,
            "description" => "Apricots"
        );

        expect($output['total'])->toBe(15.10);
        expect(count($output['results']))->toBe(7);

        $result_one = $output['results'][0];

        expect($result_one['title'])->toBe($product_one['title']);
        expect($result_one['size'])->toBe($product_one['size']);
        expect($result_one['unit_price'])->toBe($product_one['unit_price']);
        expect($result_one['description'])->toBe($product_one['description']);
    }
}
