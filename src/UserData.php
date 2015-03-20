<?php

namespace Facilis\Users;

use Doctrine\ORM\Mapping as ORM;
use Nette\Object;

/**
 * Class UserData
 * @package Facilis\Users
 *
 * @ORM\MappedSuperclass
 */
class UserData extends Object implements IUserData
{

    /**
     * @var UserAggregate
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Facilis\Users\UserAggregate", inversedBy="data")
     */
    private $user;



    public function setUser(UserAggregate $user)
    {
        $this->user = $user;
    }



    /**
     * @return UserAggregate
     */
    public function getUser()
    {
        return $this->user;
    }


}