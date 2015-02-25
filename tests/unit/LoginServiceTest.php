<?php


class LoginServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;



    protected function _before()
    {
    }



    protected function _after()
    {
    }



    // tests
    public function testSuccess()
    {
        $stateStorage = \Mockery::mock('Facilis\Users\OAuth2\IStateStorage');
        $stateStorage->shouldReceive('loadState')->andReturn('state');

        $accessToken = \Mockery::mock('League\OAuth2\Client\Token\AccessToken');
        $userDetail = \Mockery::mock('League\OAuth2\Client\Entity\User');

        $provider = \Mockery::mock('League\OAuth2\Client\Provider\ProviderInterface');
        $provider->shouldReceive('getAccessToken')->andReturn($accessToken);
        $provider->shouldReceive('getUserDetails')->andReturn($userDetail);

        $code = 'code';
        $state = 'state';

        $manager = \Mockery::mock('Facilis\Users\OAuth2\IOauthAccountManager');
        $manager->shouldReceive('persistOAuthAccount')->withArgs([get_class($provider), $accessToken, $userDetail]);

        $service = new \Facilis\Users\OAuth2\LoginService($stateStorage);
        $service->login($provider, $code, $state, $manager);
    }



    public function testNoCode()
    {
        $stateStorage = \Mockery::mock('Facilis\Users\OAuth2\IStateStorage');
        $stateStorage->shouldReceive('storeState')->andReturnNull();

        $provider = \Mockery::mock('League\OAuth2\Client\Provider\ProviderInterface');
        $provider->shouldReceive('getAuthorizationUrl')->andReturn('url');
        $provider->state = 'state';

        $code = null;
        $state = 'state';

        $manager = \Mockery::mock('Facilis\Users\OAuth2\IOauthAccountManager');

        $service = new \Facilis\Users\OAuth2\LoginService($stateStorage);
        $service->login($provider, $code, $state, $manager);

        $this->assertEquals(true, in_array('Location: url', xdebug_get_headers()));
    }



    public function testInvalidState()
    {
        $stateStorage = \Mockery::mock('Facilis\Users\OAuth2\IStateStorage');
        $stateStorage->shouldReceive('loadState')->andReturnNull();
        $stateStorage->shouldReceive('storeState');

        $provider = \Mockery::mock('League\OAuth2\Client\Provider\ProviderInterface');

        $code = 'code';
        $state = 'state';

        $manager = \Mockery::mock('Facilis\Users\OAuth2\IOauthAccountManager');

        $service = new \Facilis\Users\OAuth2\LoginService($stateStorage);
        try {
            $service->login($provider, $code, $state, $manager);
        } catch (\Facilis\Users\OAuth2\InvalidStateException $e) {
            $this->throwException($e);
        }
    }



    public function testAuthenticationException()
    {
        $stateStorage = \Mockery::mock('Facilis\Users\OAuth2\IStateStorage');
        $stateStorage->shouldReceive('loadState')->andReturn('state');
        $stateStorage->shouldReceive('storeState');

        $accessToken = \Mockery::mock('League\OAuth2\Client\Token\AccessToken');

        $provider = \Mockery::mock('League\OAuth2\Client\Provider\ProviderInterface');
        $provider->shouldReceive('getAccessToken')->andReturn($accessToken);
        $provider->shouldReceive('getUserDetails')->andThrow('League\OAuth2\Client\Exception\IDPException');

        $code = 'code';
        $state = 'state';

        $manager = \Mockery::mock('Facilis\Users\OAuth2\IOauthAccountManager');

        $service = new \Facilis\Users\OAuth2\LoginService($stateStorage);
        try {
            $service->login($provider, $code, $state, $manager);
        } catch (\Facilis\Users\OAuth2\AuthenticationException $e) {
            $this->throwException($e);
        }
    }

}