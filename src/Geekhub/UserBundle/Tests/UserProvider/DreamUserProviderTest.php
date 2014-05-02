<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.04.14
 * Time: 16:37
 */

namespace Geekhub\UserBundle\Tests\UserProvider;

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
    }

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
