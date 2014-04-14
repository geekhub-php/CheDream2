<?php

namespace Geekhub\ResourceBundle\Features\Context;

use Behat\Symfony2Extension\Context\KernelAwareInterface;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Geekhub\DreamBundle\Features\Context\FeatureContext as BaseContext;

/**
 * Feature context.
 */
class FeatureContext extends BaseContext implements KernelAwareInterface
{
}
