<?php

namespace Facilis\Users\OAuth2;

use Nette\Http\Session;
use Nette\Object;

class SessionStateStorage extends Object implements IStateStorage
{
    /**
     * @var Session
     */
    private $session;



    function __construct(Session $session)
    {
        $this->session = $session;
    }



    function storeState($state)
    {
        $this->session->getSection('fuoa2ss')->state = $state;
    }



    function loadState()
    {
        return $this->session->getSection('fuoa2ss')->state;
    }

}