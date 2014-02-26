<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.02.14
 * Time: 16:32
 */

namespace Application\Sonata\MediaBundle\DataFixtures;

use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AbstractImageLoader extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function setMediaImage($image, $src)
    {
        $media = new Media;
        $media->setBinaryContent($src.$image.'.jpg');
        $media->setProviderName('sonata.media.provider.image');

        $mediaManager = $this->container->get('sonata.media.manager.media');
        $mediaManager->save($media);

        $this->addReference('avatar'.$image, $media);
    }

    function load(ObjectManager $manager)
    {
        return array();
    }
}