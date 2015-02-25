<?php

namespace Facilis\Users\OAuth2;

interface IStateStorage
{
    function storeState($state);



    function loadState();
}