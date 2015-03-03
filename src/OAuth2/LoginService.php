<?php

namespace Facilis\Users\OAuth2;

use Facilis\Users\Manager\UserAggregateManager;
use Facilis\Users\UserAggregate;
use Facilis\Users\UserEmail;
use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Exception\IDPException;
use League\OAuth2\Client\Provider\ProviderInterface;
use Nette\Object;

class LoginService extends Object
{

    /**
     * @var IStateStorage
     */
    private $stateStorage;


    /**
     * @var IOAuthAccountManager
     */
    private $manager;



    function __construct(IStateStorage $stateStorage, IOAuthAccountManager $manager)
    {
        $this->stateStorage = $stateStorage;
        $this->manager = $manager;
    }



    public function login(ProviderInterface $provider, $code, $state)
    {
        if ($code === null) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $this->stateStorage->storeState($provider->state);
            return $authUrl;

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
                $this->managerEvent($this->manager);
                return $this->manager->persistOAuthAccount(get_class($provider), $token, $userDetails);

            } catch (IDPException $e) {
                throw new AuthenticationException;
            }
        }

    }



    protected function managerEvent($manager)
    {
        if ($manager instanceof UserAggregateManager) {
            $manager->onPersistOAuthAccount[] = function (UserAggregate $user, User $userData) {
                if ($userData->email) {
                    try {
                        $user->setEmail(new UserEmail($userData->email));
                    } catch (\InvalidArgumentException $e) {

                    }
                }
            };
        }
    }
}