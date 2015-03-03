<?php

namespace Facilis\Users;

use Doctrine\ORM\Mapping as ORM;
use Facilis\Users\OAuth2\OAuthAccount;
use Nette;

/**
 * Class UserAggregate
 * @package Facilis\Users
 *
 * @ORM\Entity
 */
class UserAggregate extends Nette\Object implements Nette\Security\IIdentity
{

    /**
     * @var mixed
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var IUserData
     *
     * @ORM\OneToOne(targetEntity="UserData", mappedBy="user", cascade={"all"})
     */
    private $data;

    /**
     * @var IUserCredentials
     *
     * @ORM\Embedded(class="UserCredentials")
     */
    private $credentials;

    /**
     * @var IUserEmail
     *
     * @ORM\Embedded(class="UserEmail")
     */
    private $email;


    /**
     * @var OAuthAccount[]
     *
     * @ORM\OneToMany(targetEntity="Facilis\Users\OAuth2\OAuthAccount", mappedBy="user", cascade={"all"})
     */
    private $oauthAccounts = [];



    /**
     * @param $id mixed
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return IUserData
     */
    public function getData()
    {
        return $this->data;
    }



    /**
     * @param IUserData $data
     */
    public function setData(IUserData $data)
    {
        $this->data = $data;
        $data->setUser($this);
    }



    /**
     * @param $username
     * @param $password
     *
     * @return bool
     */
    public function checkCredentials($username, $password)
    {
        if (!$this->credentials instanceof IUserCredentials) {
            throw new \Exception("UserAggregate::checkCredentials() demands previous use of self::setCredentials(...)");

        }
        return ($this->credentials->getUsername() == $username && $this->credentials->isPasswordValid($password));
    }



    /**
     * @param IUserCredentials $credentials
     */
    public function setCredentials(IUserCredentials $credentials)
    {
        $this->credentials = $credentials;
    }



    /**
     * Return email as string.
     *
     * @return string
     */
    public function getEmail()
    {
        if (!$this->email instanceof IUserEmail) {
            throw new \Exception("UserAggregate::getEmail() demands previous use of self::setEmail(...)");
        }

        return $this->email->getEmail();
    }



    /**
     * @param IUserEmail $email
     */
    public function setEmail(IUserEmail $email)
    {
        $this->email = $email;
    }



    /**
     * @param $service
     * @param $accessToken
     * @param $refreshToken
     * @param \DateTime $tokenExpiration
     */
    public function addOAuthAccount($service, $uid, $accessToken, $refreshToken, \DateTime $tokenExpiration)
    {
        foreach ($this->oauthAccounts as $key => $account) {
            if ($account->getService() == $service) {
                $account->update($accessToken, $refreshToken, $tokenExpiration);
                return;
            }
        }

        $this->oauthAccounts[] = new OAuthAccount($this, $service, $uid, $accessToken, $refreshToken, $tokenExpiration);
    }



    /**
     * @param $service
     *
     * @return OAuthAccount|null
     */
    public function getOAuthAccount($service)
    {
        foreach ($this->oauthAccounts as $account) {
            if ($account->getService() == $service) {
                return $account;
            }
        }
    }



    /**
     * Returns a list of roles that the user is a member of.
     * @return array
     */
    public function getRoles()
    {
        return [$this];
    }


}
