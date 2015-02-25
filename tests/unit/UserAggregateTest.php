<?php

use Facilis\Users;

class UserAggregateTest extends \Codeception\TestCase\Test
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



    public function testId()
    {
        $user = new Users\UserAggregate(1);
        $this->assertEquals(1, $user->getId());
    }



    public function testData()
    {
        $data = \Mockery::mock('Facilis\Users\IUserData');
        $user = new Users\UserAggregate(1);
        $data->shouldReceive('setUser')->withArgs([$user])->once();
        $user->setData($data);
        $this->assertEquals($data, $user->getData());
    }



    public function testNoCredentials()
    {
        try {
            $user = new Users\UserAggregate(1);
            $user->checkCredentials('user', 'password');
        } catch (\Exception $e) {
            $this->throwException($e);
        }
    }



    public function testCredentials()
    {
        $credentials = \Mockery::mock('Facilis\Users\IUserCredentials');
        $credentials->shouldReceive('getUsername')->andReturn('user');
        $credentials->shouldReceive('isPasswordValid')->andReturn(true);
        $user = new Users\UserAggregate(1);
        $user->setCredentials($credentials);
        $this->assertEquals(true, $user->checkCredentials('user', 'password'));
        $this->assertEquals(false, $user->checkCredentials('user_another', 'password'));

        $credentials = \Mockery::mock('Facilis\Users\IUserCredentials');
        $credentials->shouldReceive('getUsername')->andReturn('user');
        $credentials->shouldReceive('isPasswordValid')->andReturn(false);
        $user->setCredentials($credentials);
        $this->assertEquals(false, $user->checkCredentials('user', 'password'));
    }



    public function testNoEmail()
    {
        try {
            $user = new Users\UserAggregate(1);
            $user->getEmail();
        } catch (\Exception $e) {
            $this->throwException($e);
        }
    }



    public function testEmail()
    {
        $email = \Mockery::mock('Facilis\Users\IUserEmail');
        $email->shouldReceive('getEmail')->andReturn('test@localhost');
        $user = new Users\UserAggregate(1);
        $user->setEmail($email);
        $this->assertEquals('test@localhost', $user->getEmail());
    }



    public function testAddOAuthAccount()
    {
        $user = new Users\UserAggregate(1);
        $user->addOAuthAccount('facebook', 123, 'access', 'refresh', $time = \Mockery::mock('DateTime'));

        $account = $user->getOAuthAccount('facebook');
        $this->assertEquals($user, $account->getUserAggregate());
        $this->assertEquals(123, $account->getUid());
        $this->assertEquals('access', $account->getAccessToken());
        $this->assertEquals('refresh', $account->getRefreshToken());
        $this->assertEquals($time, $account->getTokenExpiration());

        $this->assertNull($user->getOAuthAccount('non'));
    }



    public function testUpdateOAuthAccount()
    {
        $user = new Users\UserAggregate(1);
        $user->addOAuthAccount('facebook', 123, 'access', 'refresh', $time = \Mockery::mock('DateTime'));
        $user->addOAuthAccount('facebook', 123, 'access_updated', 'refresh_updated', $time = \Mockery::mock('DateTime'));

        $account = $user->getOAuthAccount('facebook');
        $this->assertEquals('access_updated', $account->getAccessToken());
        $this->assertEquals('refresh_updated', $account->getRefreshToken());
        $this->assertEquals($time, $account->getTokenExpiration());

        $this->assertNull($user->getOAuthAccount('non'));
    }

}