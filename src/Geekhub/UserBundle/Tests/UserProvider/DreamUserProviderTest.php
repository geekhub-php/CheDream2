<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.04.14
 * Time: 16:37
 */

namespace Geekhub\UserBundle\Tests\UserProvider;

use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\EquipmentContribute;
use Geekhub\DreamBundle\Entity\FinancialContribute;
use Geekhub\DreamBundle\Entity\OtherContribute;
use Geekhub\DreamBundle\Entity\WorkContribute;
use Geekhub\UserBundle\Entity\User;
use Geekhub\UserBundle\UserProvider\DreamUserProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DreamUserProviderTest extends WebTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userManager;

    /**
     * @var DreamUserProvider
     */
    private $userProvider;

    public function setUp()
    {
        $this->userManager = $this->getMock('FOS\UserBundle\Model\UserManagerInterface');
        $this->userProvider = new DreamUserProvider($this->userManager, array());
    }

    /**
     * @dataProvider getContributionsData
     * @param User $user1
     * @param User $user2
     * @param $expect1
     * @param $expect2
     */
    public function testMergeContributions(User $user1, User $user2, $expect1, $expect2)
    {
        $this->userProvider->mergeContributions($user1, $user2);

        $this->assertCount($expect1, $user1->getFinancialContributions());
        $this->assertCount($expect2, $user2->getFinancialContributions());
        $this->assertCount($expect1, $user1->getEquipmentContributions());
        $this->assertCount($expect2, $user2->getEquipmentContributions());
        $this->assertCount($expect1, $user1->getWorkContributions());
        $this->assertCount($expect2, $user2->getWorkContributions());
        $this->assertCount($expect1, $user1->getOtherContributions());
        $this->assertCount($expect2, $user2->getOtherContributions());
    }

    /**
     * @dataProvider getDreamsData
     * @param User $user1
     * @param User $user2
     * @param $expect1
     * @param $expect2
     * @param $fExpect1
     * @param $fExpect2
     */
    public function testMergeDreams(User $user1, User $user2, $expect1, $expect2, $fExpect1, $fExpect2)
    {
        $this->userProvider->mergeDreams($user1, $user2);

        $this->assertCount($expect1, $user1->getDreams());
        $this->assertCount($expect2, $user2->getDreams());
        $this->assertCount($fExpect1, $user1->getFavoriteDreams());
        $this->assertCount($fExpect2, $user2->getFavoriteDreams());
    }

    /**
     * @param $item
     * @return User
     */
    protected function getDreamsUser($item)
    {
        $user = new User();
        for ($i = 0; $i < $item; $i++) {
            $user->addDream(new Dream());
            if (fmod($i, 3) == 0) {
                $fDream = new Dream();
                $user->addFavoriteDream($fDream->setAuthor(new User()));
            }
        }

        return $user;
    }

    /**
     * @param $item
     * @return User
     */
    protected function getContributionsUser($item)
    {
        $user = new User();
        for ($i = 0; $i < $item; $i++) {
            $user
                ->addFinancialContribution(new FinancialContribute())
                ->addEquipmentContribution(new EquipmentContribute())
                ->addWorkContribution(new WorkContribute())
                ->addOtherContribution(new OtherContribute())
            ;
        }

        return $user;
    }

    /**
     * @return array
     */
    public function getDreamsData()
    {
        return array(
            array($this->getDreamsUser(2), $this->getDreamsUser(5), 7, 0, 3, 0),
            array($this->getDreamsUser(5), $this->getDreamsUser(7), 12, 0, 5, 0),
            array($this->getDreamsUser(0), $this->getDreamsUser(5), 5, 0, 2, 0),
            array($this->getDreamsUser(10), $this->getDreamsUser(1), 11, 0, 5, 0),
            array($this->getDreamsUser(6), $this->getDreamsUser(3), 9, 0, 3, 0),
            array($this->getDreamsUser(0), $this->getDreamsUser(0), 0, 0, 0, 0),
        );
    }

    /**
     * @return array
     */
    public function getContributionsData()
    {
        return array(
            array($this->getContributionsUser(2), $this->getContributionsUser(1), 3, 0),
            array($this->getContributionsUser(1), $this->getContributionsUser(3), 4, 0),
            array($this->getContributionsUser(0), $this->getContributionsUser(1), 1, 0),
            array($this->getContributionsUser(5), $this->getContributionsUser(1), 6, 0),
            array($this->getContributionsUser(25), $this->getContributionsUser(12), 37, 0),
            array($this->getContributionsUser(99), $this->getContributionsUser(1), 100, 0),
            array($this->getContributionsUser(5), $this->getContributionsUser(4), 9, 0),
        );
    }
}
