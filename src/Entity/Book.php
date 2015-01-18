<?php

namespace Entity;

/**
* @\Doctrine\ORM\Mapping\Entity
* @\Doctrine\ORM\Mapping\Table(name="books")
*/
class Book
{
    /**
     * @\Doctrine\ORM\Mapping\Id
     * @\Doctrine\ORM\Mapping\Column(type="integer")
     * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @\Doctrine\ORM\Mapping\Column(type="string", length=256)
     */
    public $name;

    /**
     * @\Doctrine\ORM\Mapping\Column(type="string", length=2048)
     */
    public $path;

    /**
     * @\Doctrine\ORM\Mapping\Column(type="integer")
     **/
    public $user_id;

}