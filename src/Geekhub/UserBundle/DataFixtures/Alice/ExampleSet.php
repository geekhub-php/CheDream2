<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17.02.14
 * Time: 22:34
 */

$set = new h4cc\AliceFixturesBundle\Fixtures\FixtureSet();
$set->addFile(__DIR__.'/UserData.yml', 'yaml');

return $set;