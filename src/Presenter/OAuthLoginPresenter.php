<?php

namespace Facilis\Users\Presenter;

use Doctrine\ORM\EntityManager;
use Facilis\Users\OAuth2\LoginService;
use Facilis\Users\UserAggregate;
use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\Google;
use Nette\Application\UI\Presenter;

class OAuthLoginPresenter extends Presenter
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $redirect;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var LoginService
     */
    private $loginService;



    public function __construct($config, $redirect, EntityManager $entityManager, LoginService $loginService)
    {
        $this->config = $config;
        $this->redirect = $redirect;
        $this->entityManager = $entityManager;
        $this->loginService = $loginService;
    }



    public function actionLogin($provider, $code, $state)
    {
        try {
            if ($provider == 'facebook') {
                $provider = new Facebook($this->getConfig($provider));
            }

            if ($provider == 'google') {
                $provider = new Google($this->getConfig($provider));
            }

            $user = $this->loginService->login($provider, $code, $state);

            if ($user instanceof UserAggregate) {
                $this->entityManager->flush();
                $this->getUser()->login($user);
                $this->redirect($this->redirect);
            } elseif (is_string($user)) {
                $this->redirectUrl($user);
            }

        } catch (\Nette\Security\AuthenticationException $e) {
            $this->flashMessage('Authentication failed.');
            $this->redirect($this->redirect);
        }
    }



    public function renderLogin()
    {
        $this->redirect($this->redirect);
    }



    protected function getConfig($provider)
    {
        return array_merge($this->config[$provider], [
            'redirectUri' => $this->link('//login', ['provider' => $provider])
        ]);
    }


}