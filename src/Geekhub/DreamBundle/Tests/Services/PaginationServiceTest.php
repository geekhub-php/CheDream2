<?php

namespace Geekhub\DreamBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Geekhub\DreamBundle\Model\DreamsResponse;
use Geekhub\DreamBundle\Service\PaginatorService;

class PaginationServiceTest extends WebTestCase
{
    /**
     * @dataProvider providerData
     */
    public function testPaginationService($path, $count1, $pages1, $sortBy1, $sortOrder1, $count2, $pages2, $sortBy2, $sortOrder2)
    {
//        , $count2, $page2, $sortBy2, $sortOrder2
        $client = static::createClient();

        $manager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

        $client->request('GET', $path);

        $kernel = static::createKernel();
        $kernel->boot();

        $container = $kernel->getContainer();

        $response = $client->getResponse();

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $this->assertTrue($client->getResponse()->isSuccessful());

        $container = $kernel->getContainer();

        $paginator = $container->get('paginator');

        $dreamsAll = $manager->getRepository("GeekhubDreamBundle:Dream")->findAll();

        $dreams = $manager->getRepository('GeekhubDreamBundle:Dream')->findBy([], [$sortBy1 => $sortOrder1], $count1, $pages1);

        $paginationResponse1 =  $paginator->getPaginated($dreams, $count1, $pages1, $sortBy1, $sortOrder1, $dreamsAll);

        $paginationResponse2 =  $paginator->getPaginated($dreams, $count2, $pages2, $sortBy2, $sortOrder2, $dreamsAll);

        $serializer = $container->get('serializer');

        $dreamsResponse = $serializer->deserialize($response->getContent(), 'Geekhub\DreamBundle\Model\DreamsResponse', 'json');

        $this->assertEquals($paginationResponse1->getSelfPage(), $dreamsResponse->getSelfPage());
        $this->assertEquals($paginationResponse1->getNextPage(), $dreamsResponse->getNextPage());
        $this->assertEquals($paginationResponse1->getPrevPage(), $dreamsResponse->getPrevPage());
        $this->assertEquals($paginationResponse1->getFirstPage(), $dreamsResponse->getFirstPage());
        $this->assertEquals($paginationResponse1->getLastPage(), $dreamsResponse->getLastPage());

        $this->assertNotEquals($paginationResponse2->getSelfPage(), $dreamsResponse->getSelfPage());
        $this->assertNotEquals($paginationResponse2->getNextPage(), $dreamsResponse->getNextPage());
        $this->assertNotEquals($paginationResponse2->getPrevPage(), $dreamsResponse->getPrevPage());
        $this->assertNotEquals($paginationResponse2->getFirstPage(), $dreamsResponse->getFirstPage());
        $this->assertNotEquals($paginationResponse2->getLastPage(), $dreamsResponse->getLastPage());
    }

    public function providerData()
    {
        return [
            ['/api/dreams', 10, 1, 'createdAt', 'DESC', 5, 2, 'updatedAt', 'ASC'],
            ['/api/dreams?count=4&page=3&sort_by=createdAt&sort_order=ASC', 4, 3, 'createdAt', 'ASC', 5, 2, 'updatedAt', 'DESC'],
            ['/api/dreams?count=6&page=1&sort_by=updatedAt&sort_order=DESC', 6, 1, 'updatedAt', 'DESC', 5, 2, 'createdAt', 'ASC'],
            ['/api/dreams?count=3&page=2&sort_by=createdAt&sort_order=ASC', 3, 2, 'createdAt', 'ASC', 5, 2, 'updatedAt', 'DESC'],
            ['/api/dreams?count=3&page=2&sort_by=updatedAt&sort_order=DESC', 3, 2, 'updatedAt', 'DESC', 5, 2, 'createdAt', 'ASC'],
            ['/api/dreams?count=7&page=3&sort_by=createdAt&sort_order=ASC', 7, 3, 'createdAt', 'ASC', 5, 2, 'updatedAt', 'DESC'],
        ];
    }
}
