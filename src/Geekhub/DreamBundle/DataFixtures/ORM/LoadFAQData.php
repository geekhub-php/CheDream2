<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.02.14
 * Time: 16:08
 */

namespace Geekhub\DreamBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;
use Geekhub\DreamBundle\Entity\Faq;

class LoadFAQData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $FAQ = Yaml::parse($this->getYmlFile());

        foreach($FAQ as $item) {
            $faq = new Faq();
            $faq->setTitle($item['title']);
            $faq->setQuestion($item['question']);
            $faq->setAnswer($item['answer']);
            $manager->persist($faq);
        }

//        var_dump($faq, $item);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 1;
    }

    /**
     * @return string
     */
    protected function getYmlFile()
    {
        return __DIR__ . '/Data/FAQ.yml';
    }
}