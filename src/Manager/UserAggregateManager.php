<?php

namespace Facilis\Users\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Facilis\Users\OAuth2\IOAuthAccountManager;
use Facilis\Users\UserAggregate;
use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Token\AccessToken;
use Nette\Object;

class UserAggregateManager extends Object implements IOAuthAccountManager
{

    public $onPersistOAuthAccount;

    /**
     * @var EntityManager
     */
    private $entityManager;



    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }



    /**
     * Find user with OAuth2 account or create new one and update OAuth2 tokens.
     * Persist only. Do not flush!
     *
     * @param $service
     * @param AccessToken $token
     * @param User $userData
     *
     * @return UserAggregate
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function persistOAuthAccount($service, AccessToken $token, User $userData)
    {
        $uid = $userData->uid;

        try {
            $user = $this->entityManager->createQueryBuilder()
                ->select('ua')
                ->from('Facilis\Users\UserAggregate', 'ua')
                ->innerJoin('ua.oauthAccounts', 'oaa')
                ->where('oaa.service = :service')
                ->andWhere('oaa.uid = :uid')
                ->setParameter('service', $service)
                ->setParameter('uid', $uid)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();

        } catch (NoResultException $e) {
            $user = new UserAggregate();
        }

        $user->addOAuthAccount($service, $uid, $token->accessToken, $token->refreshToken,
            new \DateTime('@' . $token->expires));

        $this->onPersistOAuthAccount($user, $userData);

        $this->entityManager->persist($user);

        return $user;
    }

}