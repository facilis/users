<?php


class OAuthAccountTest extends \Codeception\TestCase\Test
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
    public function testClass()
    {
        $user = \Mockery::mock('Facilis\Users\UserAggregate');
        $account = new \Facilis\Users\OAuth2\OAuthAccount($user, 'facebook', 123, 'access', 'refresh',
            $time = \Mockery::mock('DateTime'));
        $this->assertEquals($user, $account->getUserAggregate());
        $this->assertEquals('facebook', $account->getService());
        $this->assertEquals(123, $account->getUid());
        $this->assertEquals('access', $account->getAccessToken());
        $this->assertEquals('refresh', $account->getRefreshToken());
        $this->assertEquals($time, $account->getTokenExpiration());
    }

}