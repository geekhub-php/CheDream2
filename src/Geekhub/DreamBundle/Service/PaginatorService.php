<?php

namespace Geekhub\DreamBundle\Service;

class PaginatorService
{

    public function __construct($dreamsAll, $countDreams, $currentPage)
    {
        $this->maxPerPage = 10;
        $this->currentPage = (int) $currentPage;
        $this->countDreams = (int) $countDreams;
        $this->dreamsAll = count($dreamsAll);
    }


    public function calculateNbPages()
    {
        return (int) ceil($this->dreamsAll / $this->countDreams);
    }

    /**
     * Returns whether there is next page or not.
     *
     * @return Boolean
     */
    public function hasNextPage()
    {
        return $this->currentPage < $this->getNbPages();
    }

    /**
     * Returns the number of pages.
     *
     * @return integer
     */
    public function getNbPages()
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
    public function minimumNbPages()
    {
        return 1;
    }
}