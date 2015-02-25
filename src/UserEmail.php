<?php

namespace Facilis\Users;

use Doctrine\ORM\Mapping as ORM;
use Nette\Object;
use Nette\Utils\Validators;

/**
 * Class UserEmail
 * @package Facilis\Users
 *
 * @ORM\Embeddable
 */
class UserEmail extends Object implements IUserEmail
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;



    /**
     * @param $email
     */
    public function __construct($email)
    {
        if (!Validators::isEmail($email)) {
            throw new \InvalidArgumentException("$email is not email.");
        }
        $this->email = $email;
    }



    /**
     * @return string
     */
    function getEmail()
    {
        return $this->email;
    }

}