<?php

namespace Entity;

/**
* @\Doctrine\ORM\Mapping\Entity
* @\Doctrine\ORM\Mapping\Table(name="photos")
*/
class Photo
{
    /**
     * @\Doctrine\ORM\Mapping\Id
     * @\Doctrine\ORM\Mapping\Column(type="integer")
     * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @\Doctrine\ORM\Mapping\Column(type="string", length=1024)
     */
    protected $path;
}