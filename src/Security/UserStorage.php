<?php

namespace Facilis\Users\Security;

use Doctrine\ORM\EntityManager;
use Facilis\Users\UserAggregate;
use Nette\Http\Session;
use Nette\Security\Identity;
use Nette\Security\IIdentity;

class UserStorage extends \Nette\Http\UserStorage
{

    /**
     * @var EntityManager
     */
    protected $entityManager;



    public function  __construct(Session $sessionHandler, EntityManager $entityManager)
    {
        parent::__construct($sessionHandler);
        $this->entityManager = $entityManager;
    }



    public function setIdentity(IIdentity $identity = null)
    {
        if ($identity instanceof UserAggregate) {
            $identity = new Identity(get_class($identity), [], ['id' => $identity->getId()]);
        }
        return parent::setIdentity($identity);
    }



    public function getIdentity()
    {
        $identity = parent::getIdentity();
        if ($identity !== null && $identity->getId() == 'Facilis\Users\UserAggregate' && $identity instanceof Identity) {
            $identity = $this->entityManager->getRepository('Facilis\Users\UserAggregate')->find($identity->getData()['id']);
        }
        return $identity;
    }

}