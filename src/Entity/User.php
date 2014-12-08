<?php

namespace Entity;

/**
* @\Doctrine\ORM\Mapping\Entity
* @\Doctrine\ORM\Mapping\Table(name="users")
*/
class User
{
    /**
     * @\Doctrine\ORM\Mapping\Id
     * @\Doctrine\ORM\Mapping\Column(type="integer")
     * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @\Doctrine\ORM\Mapping\Column(type="string", length=255)
     */
    protected $name;

    /**     
     * @\Doctrine\ORM\Mapping\Column(type="string", length=512, nullable=false)
     */
    private $password;

    /**
     * @\Doctrine\ORM\Mapping\Column(type="string", length=255)
     */
    private $email;
}