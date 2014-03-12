<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.03.14
 * Time: 13:43
 */

namespace Geekhub\DreamBundle\DataFixtures\ORM;

use Application\Sonata\MediaBundle\DataFixtures\ORM\AbstractMediaLoader;
use DateTime;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Geekhub\DreamBundle\Entity\Dream;
use Symfony\Component\Yaml\Yaml;

class LoadDreamData extends AbstractMediaLoader implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $dreams = Yaml::parse($this->getYmlFile());
        $tagManager = $this->container->get('geekhub.tag.tag_manager');

        foreach ($dreams as $key => $dreamData) {
            $dream = new Dream();
            $this->setMediaContent(
                __DIR__.'/images/'.$dreamData['mediaPoster'].'.jpg',
                'sonata.media.provider.image',
                'dream' . $key
            );

            $dream->setMediaPoster($this->getReference('dream'.$key));
            $dream->setAuthor($this->getReference('user-'.$dreamData['author']));
            $dream->setTitle($dreamData['title']);
            $dream->setDescription($dreamData['description']);
            $dream->setPhone($dreamData['phone']);
            $dream->setExpiredDate(new DateTime ($dreamData['expiredDate']));

            $tagManager->addTagsToEntity($dream);
            $manager->persist($dream);

            $this->addReference($key, $dream);
        }

        $manager->flush();

        foreach ($dreams as $key => $dreamData) {
            $dream = $this->getReference($key);
            $tagManager->saveTagging($dream);
        }
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

    /**
     * @return string
     */
    protected function getYmlFile()
    {
        return __DIR__.'/Data/Dream.yml';
    }
}
