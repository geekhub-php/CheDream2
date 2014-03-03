<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.03.14
 * Time: 16:15
 */

namespace Geekhub\DreamBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Geekhub\DreamBundle\Entity\Comment;
use Symfony\Component\Yaml\Yaml;

class LoadCommentData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $comments = Yaml::parse($this->getYmlFile());

        foreach ($comments as $key => $commentData) {
            $comment = new Comment();

            $comment->setDream($this->getReference($commentData['dream']));
            $comment->setUser($this->getReference('user-' . $commentData['user']));
            $comment->setText($commentData['text']);
            if (isset($commentData['parent'])) {
                $comment->setParent($this->getReference($commentData['parent']));
            }
            $this->setReference($key, $comment);

            $manager->persist($comment);
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
        return 4;
    }

    /**
     * @return string
     */
    protected function getYmlFile()
    {
        return __DIR__ . '/Data/Comment.yml';
    }
}
