<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.03.14
 * Time: 13:50
 */

namespace Geekhub\DreamBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Geekhub\DreamBundle\Entity\EquipmentResource;
use Geekhub\DreamBundle\Entity\FinancialResource;
use Geekhub\DreamBundle\Entity\WorkResource;
use Symfony\Component\Yaml\Yaml;

class LoadResourcesData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->getFinance($manager);
        $this->getEquipment($manager);
        $this->getWork($manager);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function getFinance(ObjectManager $manager)
    {
        $financeArray = $this->getFile(__DIR__.'/Data/FinancialResource.yml');

        foreach ($financeArray as $key => $financeData) {
            $dream = $this->getReference($financeData['dream']);
            $finance = new FinancialResource();

            $finance->setDream($dream);
            $finance->setTitle($financeData['title']);
            $finance->setQuantity($financeData['quantity']);

            $manager->persist($finance);
            $this->addReference($key, $finance);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    public function getEquipment(ObjectManager $manager)
    {
        $equipmentArray = $this->getFile(__DIR__.'/Data/EquipmentResource.yml');

        foreach ($equipmentArray as $key => $equipmentData) {
            $dream = $this->getReference($equipmentData['dream']);
            $equipment = new EquipmentResource();

            $equipment->setDream($dream);
            $equipment->setTitle($equipmentData['title']);
            $equipment->setQuantity($equipmentData['quantity']);
            $equipment->setQuantityType($equipmentData['quantityType']);

            $manager->persist($equipment);
            $this->addReference($key, $equipment);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    public function getWork(ObjectManager $manager)
    {
        $workArray = $this->getFile(__DIR__.'/Data/WorkResource.yml');

        foreach ($workArray as $key => $workData) {
            $dream = $this->getReference($workData['dream']);
            $work = new WorkResource();

            $work->setDream($dream);
            $work->setTitle($workData['title']);
            $work->setQuantity($workData['quantity']);
            $work->setQuantityDays($workData['quantityDays']);

            $manager->persist($work);
            $this->addReference($key, $work);
        }
    }

    /**
     * @param $file
     *
     * @return array
     */
    public function getFile($file)
    {
        return Yaml::parse($file);
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
}
