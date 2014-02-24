<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17.02.14
 * Time: 22:34
 */

$set = new h4cc\AliceFixturesBundle\Fixtures\FixtureSet();
$set->addFile(__DIR__.'/../../../DreamBundle/DataFixtures/Alice/FAQData.yml', 'yaml');
$set->addFile(__DIR__.'/../../../UserBundle/DataFixtures/Alice/UserData.yml', 'yaml');
$set->addFile(__DIR__.'/../../../DreamBundle/DataFixtures/Alice/DreamData.yml', 'yaml');

return $set;