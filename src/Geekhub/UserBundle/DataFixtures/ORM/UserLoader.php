<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.02.14
 * Time: 22:59
 */

namespace Geekhub\UserBundle\DataFixtures\ORM;

use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class UserLoader extends DataFixtureLoader implements OrderedFixtureInterface
{
    /**
     * Returns an array of file paths to fixtures
     *
     * @return array<string>
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
    function getOrder()
    {
        return 2;
    }

    public function getMediaImage($file)
    {
        $media = new Media();
        $media->setBinaryContent($file);
        $media->setProviderName('sonata.media.provider.image');

        $mediaManager = $this->container->get('sonata.media.manager.media');
        $mediaManager->save($media);

        return $media;
    }
}