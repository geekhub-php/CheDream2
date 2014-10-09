<?php

namespace Geekhub\DreamBundle\Entity;

interface EventInterface
{
    public function getCreatedAt();

    public function getEventImage();

    public function getEventTitle();
}
