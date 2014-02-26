<?php
/*
 * Created by PhpStorm.
 * User: alex
 * Date: 25.02.14
 * Time: 21:19
 */

namespace Geekhub\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Application\Sonata\MediaBundle\Entity\Media;

class MediaLoader extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $images = $this->getImage();

        foreach ($images as $image) {
            $media = new Media;
            $media->setBinaryContent(__DIR__.'/../Data/'.$image.'.jpg');
            $media->setProviderName('sonata.media.provider.image');

            $mediaManager = $this->container->get('sonata.media.manager.media');
            $mediaManager->save($media);

            $this->addReference('avatar'.$image, $media);
        }
        $this->setUserAvatar();
    }

    private function setUserAvatar()
    {
        $em = $this->container->get('doctrine')->getManager();
        $users = $em->getRepository('GeekhubUserBundle:User')->findAll();

        foreach ($users as $user) {
            $reference = $this->getReference('avatar'.$user->getUsername());
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
