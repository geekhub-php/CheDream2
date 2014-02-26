<?php
/*
 * Created by PhpStorm.
 * User: alex
 * Date: 25.02.14
 * Time: 21:19
 */

namespace Geekhub\UserBundle\DataFixtures\ORM;

use Application\Sonata\MediaBundle\DataFixtures\AbstractImageLoader;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MediaLoader extends AbstractImageLoader implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $images = $this->getImage();

        foreach ($images as $image) {
            $this->setMediaImage($image, __DIR__.'/../Data/');
        }
        $this->setUserAvatar();
    }

    private function setUserAvatar()
    {
        $em = $this->container->get('doctrine')->getManager();
        $users = $em->getRepository('GeekhubUserBundle:User')->findAll();

        foreach ($users as $user) {
            $user->setAvatar($this->getReference('avatar'.$user->getUsername()));
            $em->persist($user);
        }

        $em->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }

    private function getImage()
    {
        return array('yoda', 'dartvaider', 'chewbacca', 'c3pio');
    }
}
