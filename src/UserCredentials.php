<?php

namespace Facilis\Users;

use Doctrine\ORM\Mapping as ORM;
use Nette\Object;
use Nette\Security\Passwords;

/**
 * Class UserCredentials
 * @package Facilis\Users
 *
 * @ORM\Embeddable
 */
class UserCredentials extends Object implements IUserCredentials
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $hash;



    /**
     * @param $username
     * @param $password
     */
    function __construct($username, $password)
    {
        $this->username = $username;
        $this->hash = Passwords::hash($password);
    }



    /**
     * @return string
     */
    function getUsername()
    {
        return $this->username;
    }



    /**
     * @param $password
     *
     * @return bool
     */
    function isPasswordValid($password)
    {
        return Passwords::verify($password, $this->hash);
    }

}