<?php

namespace Geekhub\DreamBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Behat\Context\Step;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

/**
 * Feature context.
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
{
    private $kernel;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When /^I wait (\d+) seconds?$/
     */
    public function waitSeconds($seconds)
    {
        $this->getSession()->wait(1000*$seconds);
    }

    /**
     * @Given /^I am login as "([^"]*)" with password "([^"]*)"$/
     */
    public function loginUserWithPassword($username, $password)
    {
        return array(
            new Step\Given('I am on "/login"'),
            new Step\When("fill in \"username\" with \"$username\""),
            new Step\When("fill in \"password\" with \"$password\""),
            new Step\When("I press \"_submit\""),
        );
    }

    /**
     * @Given /^I fill in hidden "([^"]*)" with "([^"]*)"$/
     */
    public function iFillInHiddenFieldWithValue($field, $value)
    {
        $javascript = "document.getElementById('".$field."').value='".$value."'";
        $this->getSession()->executeScript($javascript);
    }

    /**
     * @Given /^I fill in hiddenImage "([^"]*)" with "([^"]*)"$/
     */
    public function iFillInHiddenImageWithValue($field, $value)
    {
        $javascript = "document.getElementById('".$field."').value='".$value."'";
        $this->getSession()->executeScript($javascript);
    }

    /**
     * @Given /^I fill in tinymce "([^"]*)" with "([^"]*)"$/
     */
    public function fillInTinyMce($tinyId, $value)
    {
        $javascript = "tinymce.EditorManager.get('$tinyId').setContent('$value');";
        $this->getSession()->executeScript($javascript);
    }
}
