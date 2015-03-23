<?php

namespace Geekhub\DreamBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Request\ParamFetcher;
use Geekhub\DreamBundle\Model\DreamsResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class PaginatorService
{
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getPaginated($count,
                                 $pages,
                                 $sortBy,
                                 $sortOrder,
                                 $dreamsAll
    ) {
        $this->count = $count;
        $this->pages = $pages;
        $this->sortBy = $sortBy;
        $this->sortOrder = $sortOrder;
        $this->maxPerPage = 10;
        $this->dreamsAll = count($dreamsAll);

        $dreamsResponse = new DreamsResponse();

        $dreamsResponse->setSelfPage($this->getSelfPage());
        $dreamsResponse->setNextPage($this->getNextPage());
        $dreamsResponse->setPrevPage($this->getPrevPage());
        $dreamsResponse->setFirstPage($this->getFirstPage());
        $dreamsResponse->setLastPage($this->getLastPage());

        return $dreamsResponse;
    }

    private function calculateNbPages()
    {
        return (int) ceil($this->dreamsAll / (int) $this->count);
    }

    /**
     * Returns whether there is next page or not.
     *
     * @return Boolean
     */
    private function hasNextPage()
    {
        return (int) $this->pages < $this->getNbPages();
    }

    /**
     * Returns the number of pages.
     *
     * @return integer
     */
    private function getNbPages()
    {
        $nbPages = $this->calculateNbPages();

        if ($nbPages == 0) {
            return $this->minimumNbPages();
        }

        return $nbPages;
    }

    /**
     * Return minimum number pages
     *
     * @return int
     */
    private function minimumNbPages()
    {
        return 1;
    }

    private function getSelfPage()
    {
        return $this->hasNextPage() ?
            $this->router->generate('get_dreams', [
                    'count' => $this->count,
                    'page' => $this->pages,
                    'sort_by' => $this->sortBy,
                    'sort_order' => $this->sortOrder,
                ]
            ) :
            'false';
    }

    private function getNextPage()
    {
        return $this->hasNextPage() ?
            $this->router->generate('get_dreams', [
                    'count' => $this->count,
                    'page' => $this->pages + 1,
                ]
            ) :
            'false';
    }

    private function getPrevPage()
    {
        return $this->hasNextPage() ?
            $this->router->generate('get_dreams', [
                    'count' => $this->count,
                    'page' => $this->pages - 1,
                ]
            ) :
            'false';
    }

    private function getFirstPage()
    {
        return $this->hasNextPage() ?
            $this->router->generate('get_dreams', [
                    'count' => $this->count,
                    'page' => $this->minimumNbPages(),
                ]
            ) :
            'false';
    }

    private function getLastPage()
    {
        return $this->hasNextPage() ?
            $this->router->generate('get_dreams', [
                    'count' => $this->count,
                    'page' => $this->calculateNbPages(),
                ]
            ) :
            'false';
    }
}
