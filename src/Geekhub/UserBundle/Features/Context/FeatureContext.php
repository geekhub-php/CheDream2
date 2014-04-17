<?php

namespace Geekhub\UserBundle\Features\Context;

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
     * @Given /^I am Executing last merging accounts query$/
     */
    public function executeLastUserMergeAccountsQuery()
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();
        $lastHash = $em->getRepository('GeekhubUserBundle:MergeRequest')->findOneBy(array())->getHash();

        return array(
            new Step\Given('I am on "/user/mergeAccounts/'.$lastHash.'"'),
        );
    }

}
