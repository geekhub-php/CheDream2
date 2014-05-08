<?php

namespace Geekhub\UserBundle\Tests\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Geekhub\DreamBundle\Entity\AbstractContribute;
use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\EquipmentContribute;
use Geekhub\DreamBundle\Entity\FinancialContribute;
use Geekhub\DreamBundle\Entity\WorkContribute;
use Geekhub\DreamBundle\Entity\OtherContribute;
use Geekhub\ResourceBundle\Tests\SecurityMethods;
use Geekhub\UserBundle\Controller\UserController;
use Geekhub\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserControllerTest extends WebTestCase
{
    /**
     * @var \Geekhub\UserBundle\Controller\UserController
     */
    private $controller;

    /**
     * @var \Geekhub\ResourceBundle\Tests\SecurityMethods
     */
    private $securityMethods;

    public function setUp()
    {
        $this->controller = new UserController();
        $this->securityMethods = new SecurityMethods();
    }

    public function testFailLoginAction()
    {
        $client = static::createClient();

        $crawler = $this->loginAs($client, 'undefined', 'undefined');

        $this->assertEquals('/login', $client->getRequest()->getPathInfo());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Invalid username or password")')->count());
    }

    public function testLoginAction()
    {
        $client = static::createClient();

        $this->loginAs($client, 'admin', 'admin');

        $this->assertEquals("/", $client->getRequest()->getPathInfo());
    }

    public function testFakeEmailRedirectAction()
    {
        $client = static::createClient();

        $this->loginAs($client, 'darthVader', 'darthVader');
        $client->followRedirect();

        $this->assertEquals("/connect-account/user/updateContacts", $client->getRequest()->getPathInfo());
    }

    public function testChangeFakeEmailAction()
    {
        $client = static::createClient();

        $this->loginAs($client, 'darthVader', 'darthVader');
        $crawler = $client->followRedirect();

        $form = $crawler->selectButton('_submit')->form();
        $form['newUserForm[email]'] = 'darthVader@gmail.com';
        $client->submit($form);

        $client->followRedirect();

        $this->assertEquals("/", $client->getRequest()->getPathInfo());
    }

    public function testEditProfileAction()
    {
        $client = static::createClient();

        $this->loginAs($client, 'darthVader', 'darthVader');

        $crawler = $client->request('GET', '/user/edit');

        $avatar = realpath(__DIR__ . '/../../DataFixtures/ORM/images/darthVader.jpg');

        $form = $crawler->selectButton('_submit')->form();
        $form['newUserForm[firstName]'] = 'Darth';
        $form['newUserForm[lastName]']  = 'Vader';
        $form['newUserForm[email]'] = 'darthVader@yahoo.com';
        $form['newUserForm[about]'] = 'Who is your daddy?';
        $form['newUserForm[phone]'] = '555-5555';
        $form['newUserForm[skype]'] = 'darthVader_skype';
        $form['newUserForm[birthday][day]']->select('1');
        $form['newUserForm[birthday][month]']->select('1');
        $form['newUserForm[birthday][year]']->select('1985');
        $form['dream-poster']->upload(new UploadedFile($avatar, 'darthVader'));

        $client->submit($form);

        $dart = $client->getContainer()->get('doctrine')->getRepository('GeekhubUserBundle:User')->findOneByUsername('darthVader');
        $crawler = $client->request('GET', '/users/' . $dart->getId());

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Darth Vader")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Who is your daddy?")')->count());
    }

    public function testChangeEmailToFakeAction()
    {
        $client = static::createClient();

        $this->loginAs($client, 'darthVader', 'darthVader');

        $crawler = $client->request('GET', '/user/edit');

        $avatar = realpath(__DIR__ . '/../../DataFixtures/ORM/images/darthVader.jpg');

        $form = $crawler->selectButton('_submit')->form();
        $form['newUserForm[email]'] = 'darthVader@example.com';
        $form['dream-poster']->upload(new UploadedFile($avatar, 'darthVader'));

        $client->submit($form);

        $client->request('GET', '/user/edit');
        $client->followRedirect();

        $this->assertEquals("/connect-account/user/updateContacts", $client->getRequest()->getPathInfo());
    }

    /**
     * @param  Client $client
     * @param  string $username
     * @param  string $password
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function loginAs(Client $client, $username, $password)
    {
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('_submit')->form();

        $form['_username'] = $username;
        $form['_password'] = $password;

        $client->submit($form);

        return $client->followRedirect();
    }

    /**
     * @dataProvider getContributionsData
     * @param User $user
     * @param $expect
     */
    public function testGetContributions(User $user, $expect)
    {
        $contributions = $this->securityMethods->invokeMethod($this->controller, 'getContributions', array($user));

        $this->assertEquals($expect, $contributions->count());
    }

    /**
     * @return array
     */
    public function getContributionsData()
    {
        return array(
            array($this->getUser(1, 2, 3, 0), 6),
            array($this->getUser(0, 2, 3, 0), 5),
            array($this->getUser(1, 2, 3, 1), 7),
            array($this->getUser(1, 2, 0, 2), 5),
            array($this->getUser(1, 1, 1, 1), 4),
            array($this->getUser(66, 22, 13, 20), 121)
        );
    }

    /**
     * @param null $financeItems
     * @param null $equipmentItems
     * @param null $workItems
     * @param null $otherItems
     *
     * @return User
     */
    protected function getUser($financeItems = null, $equipmentItems = null, $workItems = null, $otherItems = null)
    {
        $user = new User();

        for ($i = 0; $i < $financeItems; $i++) {
            $user->addFinancialContribution(new FinancialContribute());
        }

        for ($i = 0; $i < $equipmentItems; $i++) {
            $user->addEquipmentContribution(new EquipmentContribute());
        }

        for ($i = 0; $i < $workItems; $i++) {
            $user->addWorkContribution(new WorkContribute());
        }

        for ($i = 0; $i < $otherItems; $i++) {
            $user->addOtherContribution(new OtherContribute());
        }

        return $user;
    }

    /**
     * @dataProvider getContributionsDreamData
     * @param ArrayCollection $contributions
     */
    public function testGetContributionsDream(ArrayCollection $contributions)
    {
        $dreams = $this->securityMethods->invokeMethod($this->controller, 'getContributionsDream', array($contributions));

        var_dump($dreams->count());
    }

    protected function getContributions($item)
    {
        $contributions = new ArrayCollection();
        $dreams = new ArrayCollection();
        for ($i = 1; $i <= 10; $i++) {
            $dreams->add(new Dream());
        }

        for ($i = 0; $i < $item-2; $i++) {
            $contribute = new AbstractContribute();
            $contribute->setDream($dreams->last());
            $contributions->add($contribute);
        }

        for ($i = $item-2; $i < $item; $i++) {
            $contribute = new AbstractContribute();
            $contribute->setDream($dreams->first());
            $contributions->add($contribute);
        }

        for ($i = $item; $i < $item+3; $i++) {
            $contribute = new AbstractContribute();
            $contribute->setDream($dreams->get(5));
            $contributions->add($contribute);
        }

        return $contributions;
    }

    public function getContributionsDreamData()
    {
        return array(
            array($this->getContributions(12))
        );
    }
}
