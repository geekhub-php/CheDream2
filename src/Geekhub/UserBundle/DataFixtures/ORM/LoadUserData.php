<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.03.14
 * Time: 22:54
 */

namespace Geekhub\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Geekhub\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        foreach($this->getUserArray() as $item) {
            $user = new User();
            $user->setUsername($item);
            $user->setEmail($item.'@example.com');
            $user->setEnabled(true);
            $user->setPassword($item);
            $user->setFirstName($item);
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 2;
    }

    /**
     * @return array
     */
    protected function getUserArray()
    {
        return array(
            'yoda',
            'dartvaider',
            'chewbacca',
            'c3pio',
        );
    }
}