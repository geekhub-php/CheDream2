<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.02.14
 * Time: 22:59
 */

namespace Geekhub\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class UserLoader extends DataFixtureLoader implements OrderedFixtureInterface
{
    /**
     * Returns an array of file paths to fixtures
     *
     * @return string[]
     */
    protected function getFixtures()
    {
        return  array(
            __DIR__ . '/../Alice/UserData.yml',
        );
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
