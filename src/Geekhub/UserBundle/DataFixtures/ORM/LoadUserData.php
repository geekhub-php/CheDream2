<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.03.14
 * Time: 22:54
 */

namespace Geekhub\UserBundle\DataFixtures\ORM;

use Application\Sonata\MediaBundle\DataFixtures\ORM\AbstractMediaLoader;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Geekhub\UserBundle\Entity\User;

class LoadUserData extends AbstractMediaLoader implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserArray() as $item) {
            $this->setMediaContent(
                __DIR__.'/images/'.$item.'.jpg',
                'sonata.media.provider.image',
                'avatar'.$item
            );

            $user = new User();
            $user->setUsername($item);
            $user->setEmail($item.'@example.com');
            $user->setEnabled(true);
            $user->setPassword($item);
            $user->setFirstName($item);
            $user->setAvatar($this->getReference('avatar'.$item));
            $manager->persist($user);

            $this->addReference('user-'.$item, $user);
        }

        $manager->flush();
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

    /**
     * @return string[]
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
