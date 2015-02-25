<?php

namespace Facilis\Users;

use Doctrine\ORM\Mapping as ORM;
use Nette\Object;

/**
 * Class UserData
 * @package Facilis\Users
 *
 * @ORM\Entity
 */
class UserData extends Object implements IUserData
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var UserAggregate
     *
     * @ORM\OneToOne(targetEntity="Facilis\Users\UserAggregate", inversedBy="data")
     */
    private $user;



    public function __construct($id = null)
    {
        $this->id = $id;
    }



    public function setUser(UserAggregate $user)
    {
        $this->user = $user;
    }



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return UserAggregate
     */
    public function getUser()
    {
        return $this->user;
    }


}