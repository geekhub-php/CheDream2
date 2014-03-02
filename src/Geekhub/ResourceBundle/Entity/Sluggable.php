<?php

namespace Geekhub\ResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait Sluggable
{
    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=200, unique=true)
     */
    protected $slug;

    /**
     * Set slug
     *
     * @param  string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
