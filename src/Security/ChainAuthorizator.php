<?php

namespace Facilis\Users\Security;

use Nette\Object;
use Nette\Security\IAuthorizator;
use Nette\Security\privilege;
use Nette\Security\role;

class ChainAuthorizator extends Object implements IAuthorizator
{

    /**
     * @var IAuthorizator[]
     */
    private $authorizators = [];



    function __construct($authorizators)
    {
        foreach ($authorizators as $authorizator) {
            if (!$authorizator instanceof IAuthorizator) {
                throw new \InvalidArgumentException("Authorizator must implement IAuthorizator.");
            }
        }
        $this->authorizators = $authorizators;
    }



    /**
     * Performs a role-based authorization.
     *
     * @param  string  role
     * @param  string  resource
     * @param  string  privilege
     *
     * @return bool
     */
    function isAllowed($role, $resource, $privilege)
    {
        foreach ($this->authorizators as $authorizator) {
            $result = $authorizator->isAllowed($role, $resource, $privilege);
            if($result === null) {
                continue;
            }
            return $result;
        }
        return false;
    }

}