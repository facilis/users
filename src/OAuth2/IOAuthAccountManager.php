<?php

namespace Facilis\Users\OAuth2;

use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Token\AccessToken;

interface IOAuthAccountManager
{
    function persistOAuthAccount($service, AccessToken $token, User $userData);
}