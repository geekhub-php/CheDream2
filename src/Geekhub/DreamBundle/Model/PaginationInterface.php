<?php

namespace Geekhub\DreamBundle\Model;

interface PaginationInterface
{
    public function setSelfPage($selfPage);

    public function getSelfPage();

    public function setNextPage($nextPage);

    public function getNextPage();

    public function setPrevPage($prevPage);

    public function getPrevPage();

    public function setFirstPage($firstPage);

    public function getFirstPage();

    public function setLastPage($lastPage);

    public function getLastPage();
}