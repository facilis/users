<?php

namespace Facilis\Users\OAuth2;

use League\OAuth2\Client\Exception\IDPException;
use League\OAuth2\Client\Provider\ProviderInterface;
use Nette\Object;

class LoginService extends Object
{

    /**
     * @var IStateStorage
     */
    private $stateStorage;



    function __construct(IStateStorage $stateStorage)
    {
        $this->stateStorage = $stateStorage;
    }



    public function login(ProviderInterface $provider, $code, $state, IOAuthAccountManager $manager)
    {
        if ($code === null) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $this->stateStorage->storeState($provider->state);
            header('Location: ' . $authUrl);

            // Check given state against previously stored one to mitigate CSRF attack
        } elseif ($state === null || ($state !== $this->stateStorage->loadState())) {
            $this->stateStorage->storeState(null);
            throw new InvalidStateException();

        } else {

            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $code
            ]);

            // Optional: Now you have a token you can look up a users profile data
            try {

                // We got an access token, let's now get the user's details
                $userDetails = $provider->getUserDetails($token);

                $manager->persistOAuthAccount(get_class($provider), $token, $userDetails);

            } catch (IDPException $e) {
                throw new AuthenticationException;
            }
        }

    }
}