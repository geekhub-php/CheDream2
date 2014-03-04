<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.03.14
 * Time: 16:55
 */

namespace Geekhub\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class LoadFavoriteData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $favorites = Yaml::parse($this->getYmlFile());

        foreach($favorites as $favoriteData) {
            $user = $manager->getRepository('GeekhubUserBundle:User')
                ->findOneBy(['username' => $favoriteData['user']]);

            foreach($favoriteData['dreams'] as $dreamData) {
                $dream = $this->getReference($dreamData);
                $user->addFavoriteDream($dream);
            }

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
        return 4;
    }

    /**
     * @return string
     */
    protected function getYmlFile()
    {
        return __DIR__ . '/Data/Favorite.yml';
    }
}