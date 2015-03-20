<?php


class UserDataTest extends \Codeception\TestCase\Test
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
        $data = new \Facilis\Users\UserData();
        $data->setUser($user);

        $this->assertEquals($user, $data->getUser());
    }

}