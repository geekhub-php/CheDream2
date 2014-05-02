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
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DreamUserProviderTest extends WebTestCase
{
    /**
     * @dataProvider getData
     * @param User $user1
     * @param User $user2
     * @param $expect
     */
    public function testMergeContributions(User $user1, User $user2, $expect)
    {
        $this->assertEquals($expect, count($user1->getFinancialContributions()));
    }

    protected function getUser($item)
    {
        $user = new User();
        for ($i = 0; $i < $item; $i++ ) {
            $user->addFinancialContribution(new FinancialContribute())
                ->addEquipmentContribution(new EquipmentContribute())
                ->addWorkContribution(new WorkContribute())
                ->addOtherContribution(new OtherContribute())
            ;
        }

        return $user;
    }

    public function getData()
    {
        return array(
            array($this->getUser(2), $this->getUser(1), 3),
        );
    }
}
