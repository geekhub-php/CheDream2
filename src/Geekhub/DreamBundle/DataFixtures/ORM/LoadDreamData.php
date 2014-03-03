<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.03.14
 * Time: 13:43
 */

namespace Geekhub\DreamBundle\DataFixtures\ORM;

use Application\Sonata\MediaBundle\DataFixtures\ORM\AbstractMediaLoader;
use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDreamData extends AbstractMediaLoader implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // TODO: Implement load() method.
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}
