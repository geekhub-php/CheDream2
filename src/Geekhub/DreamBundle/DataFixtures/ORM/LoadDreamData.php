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
use Geekhub\DreamBundle\Entity\Status;
use Symfony\Component\Finder\Finder;
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
        $counter = $this->addPictures();

        foreach ($dreams as $key => $dreamData) {
            $dream = new Dream();
            $this->setMediaContent(
                __DIR__.'/images/'.$dreamData['mediaPoster'].'.jpg',
                'poster',
                'sonata.media.provider.image',
                'dream' . $key
            );

            $dream->setMediaPoster($this->getReference('dream'.$key));
            $dream->setAuthor($this->getReference('user-'.$dreamData['author']));
            $dream->setTitle($dreamData['title']);
            $dream->setDescription($dreamData['description']);
            $dream->setPhone($dreamData['phone']);
            $dream->setExpiredDate(new DateTime ($dreamData['expiredDate']));

            $dream->setTags($dreamData['tags']);
            $tagManager->addTagsToEntity($dream);
            for ($i = 0; $i < $counter; $i++) {
                $dream->addMediaPicture($this->getReference('media'.$i));
            }

            $manager->persist($dream);

            $this->addReference($key, $dream);
            if ($dream->getCurrentStatus() != $dreamData['status']) {
                switch ($dreamData['status']) {
                    case 'success':
                        $dream-> addStatus(new Status(Status::SUCCESS));
                        break;
                    case 'collecting-resources':
                        $dream-> addStatus(new Status(Status::COLLECTING_RESOURCES));
                        break;
                    case 'implementing':
                        $dream-> addStatus(new Status(Status::IMPLEMENTING));
                        break;
                    default:
                        $dream-> addStatus(new Status(Status::SUBMITTED));
                }
                $manager->persist($dream);
            }
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

    /**
     * @return int
     */
    private function addPictures()
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/images');
        $counter = 0;

        foreach ($finder as $file) {
            $this->setMediaContent(
                __DIR__.'/images/'.$file->getRelativePathname(),
                'pictures',
                'sonata.media.provider.image',
                'media'.$counter
            );

            $counter++;
        }

        return $counter;
    }
}
