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
use Geekhub\DreamBundle\Entity\EquipmentContribute;
use Geekhub\DreamBundle\Entity\FinancialContribute;
use Geekhub\DreamBundle\Entity\WorkContribute;
use Symfony\Component\Yaml\Yaml;

class LoadContributeData extends AbstractFixture implements OrderedFixtureInterface
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
        $financeArray = $this->getFile(__DIR__.'/Data/FinancialContribute.yml');

        foreach ($financeArray as $financeData) {
            $dream = $this->getReference($financeData['dream']);
            $resource = $this->getReference($financeData['resource']);
            $user = $this->getReference('user-'.$financeData['user']);
            $finance = new FinancialContribute();
            $finance->setDream($dream);
            $finance->setUser($user);
            $finance->setHiddenContributor($financeData['hidden']);
            $finance->setQuantity($financeData['quantity']);
            $finance->setFinancialArticle($resource);
            $manager->persist($finance);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    public function getEquipment(ObjectManager $manager)
    {
        $equipmentArray = $this->getFile(__DIR__.'/Data/EquipmentContribute.yml');

        foreach ($equipmentArray as $equipmentData) {
            $dream = $this->getReference($equipmentData['dream']);
            $resource = $this->getReference($equipmentData['resource']);
            $user = $this->getReference('user-'.$equipmentData['user']);
            $equipment = new EquipmentContribute();
            $equipment->setDream($dream);
            $equipment->setUser($user);
            $equipment->setHiddenContributor($equipmentData['hidden']);
            $equipment->setQuantity($equipmentData['quantity']);
            $equipment->setEquipmentArticle($resource);
            $manager->persist($equipment);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    public function getWork(ObjectManager $manager)
    {
        $workArray = $this->getFile(__DIR__.'/Data/WorkContribute.yml');

        foreach ($workArray as $workData) {
            $dream = $this->getReference($workData['dream']);
            $resource = $this->getReference($workData['resource']);
            $user = $this->getReference('user-'.$workData['user']);
            $work = new WorkContribute();
            $work->setDream($dream);
            $work->setUser($user);
            $work->setHiddenContributor($workData['hidden']);
            $work->setQuantity($workData['quantity']);
            $work->setQuantityDays($workData['quantityDays']);
            $work->setWorkArticle($resource);
            $manager->persist($work);
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
        return 5;
    }
}
