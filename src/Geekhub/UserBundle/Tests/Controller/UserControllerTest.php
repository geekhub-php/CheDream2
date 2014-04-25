<?php

namespace Geekhub\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserControllerTest extends WebTestCase
{
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

        $filesystem = new Filesystem;
        $filesystem->dumpFile(
            $client->getKernel()->getRootDir() . '/cache/web/test.html',
            $client->getResponse()->getContent()
        );

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Darth Vader")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Who is your daddy?")')->count());
    }

    public function testChangeEmailAction()
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
     * @param  Client                                $client
     * @param  string                                $username
     * @param  string                                $password
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
}
