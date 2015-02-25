<?php


class UserAggregateManagerTest extends \Codeception\TestCase\Test
{
    /**
     * @var \FunctionalTester
     */
    protected $tester;



    protected function _before()
    {
        buildSchema();
    }



    protected function _after()
    {
    }



    // tests
    public function testPersistOAuthAccount()
    {
        $em = \Codeception\Module\Doctrine2::$em;
        $manager = new \Facilis\Users\Manager\UserAggregateManager($em);


        // Create first user
        $accessToken = \Mockery::mock('League\OAuth2\Client\Token\AccessToken');
        $accessToken->accessToken = 'accessToken1';
        $accessToken->refreshToken = 'refreshToken1';
        $accessToken->expires = time() + 15000;
        $userData = \Mockery::mock('League\OAuth2\Client\Entity\User');
        $userData->uid = 1;
        $user1 = $manager->persistOAuthAccount('facebook', $accessToken, $userData);

        $this->assertEquals($user1->getOAuthAccount('facebook')->getAccessToken(), 'accessToken1');
        $this->assertEquals($user1->getOAuthAccount('facebook')->getRefreshToken(), 'refreshToken1');

        $this->tester->flushToDatabase();


        // Create second user - another
        $accessToken = \Mockery::mock('League\OAuth2\Client\Token\AccessToken');
        $accessToken->accessToken = 'accessToken2';
        $accessToken->refreshToken = 'refreshToken2';
        $accessToken->expires = time() + 15000;
        $userData = \Mockery::mock('League\OAuth2\Client\Entity\User');
        $userData->uid = 2;
        $user2 = $manager->persistOAuthAccount('facebook', $accessToken, $userData);

        $this->assertEquals($user2->getOAuthAccount('facebook')->getAccessToken(), 'accessToken2');
        $this->assertEquals($user2->getOAuthAccount('facebook')->getRefreshToken(), 'refreshToken2');

        $this->tester->flushToDatabase();
        $this->assertNotEquals($user1, $user2);


        // Update second user
        $accessToken = \Mockery::mock('League\OAuth2\Client\Token\AccessToken');
        $accessToken->accessToken = 'accessToken_updated';
        $accessToken->refreshToken = 'refreshToken_updated';
        $accessToken->expires = time() + 15000;
        $userData = \Mockery::mock('League\OAuth2\Client\Entity\User');
        $userData->uid = 2;
        $user3 = $manager->persistOAuthAccount('facebook', $accessToken, $userData);

        $this->tester->flushToDatabase();
        $this->assertEquals($user2, $user3); // The user must be the same.

        $this->assertEquals($user3->getOAuthAccount('facebook')->getAccessToken(), 'accessToken_updated');
        $this->assertEquals($user3->getOAuthAccount('facebook')->getRefreshToken(), 'refreshToken_updated');
    }

}