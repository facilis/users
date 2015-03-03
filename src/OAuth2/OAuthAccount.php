<?php

namespace Facilis\Users\OAuth2;

use Doctrine\ORM\Mapping as ORM;
use Facilis\Users\UserAggregate;
use Nette;

/**
 * Class OAuthAccount
 * @package Facilis\Users\OAuth2
 *
 * @ORM\Entity
 */
class OAuthAccount extends Nette\Object
{

    /**
     * @var UserAggregate
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Facilis\Users\UserAggregate", inversedBy="oauthAccounts")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $service;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $accessToken;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $refreshToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tokenExpiration;



    /**
     * @param $service
     * @param $accessToken
     * @param $refreshToken
     * @param $tokenExpiration
     */
    function __construct(UserAggregate $user, $service, $uid, $accessToken, $refreshToken, \DateTime $tokenExpiration)
    {
        $this->user = $user;
        $this->service = $service;
        $this->uid = $uid;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->tokenExpiration = $tokenExpiration;
    }



    public function update($accessToken, $refreshToken, \DateTime $tokenExpiration)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->tokenExpiration = $tokenExpiration;
    }



    public function getUserAggregate()
    {
        return $this->user;
    }



    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }



    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }



    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }



    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }



    /**
     * @return \DateTime
     */
    public function getTokenExpiration()
    {
        return $this->tokenExpiration;
    }


}