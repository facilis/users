<?php

namespace Facilis\Users\Presenter;

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
     * @var LoginService
     */
    private $loginService;



    public function __construct($config, $redirect, LoginService $loginService)
    {
        $this->config = $config;
        $this->redirect = $redirect;
        $this->loginService = $loginService;
    }



    public function actionLogin($provider, $code, $state, $request)
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
                $this->getUser()->login($user);
                $lastRequest = $this->getLastRequest();
                if ($lastRequest) {
                    $this->restoreRequest($lastRequest);
                } else {
                    $this->redirect($this->redirect);
                }
            } elseif (is_string($user)) {
                $this->setLastRequest($request);
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



    private function getLastRequest()
    {
        return $this->getSession('facilis.oauth.request')->request;
    }



    private function setLastRequest($request)
    {
        $this->getSession('facilis.oauth.request')->request = $request;
    }

}