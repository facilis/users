<?php


class UserCredentialsTest extends \Codeception\TestCase\Test
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
        $credentials = new \Facilis\Users\UserCredentials('username', 'password');
        $this->assertEquals('username', $credentials->getUsername());
        $this->assertEquals(true, $credentials->isPasswordValid('password'));
        $this->assertEquals(false, $credentials->isPasswordValid('password_wrong'));
    }

}