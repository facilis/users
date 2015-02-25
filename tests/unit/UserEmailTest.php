<?php


class UserEmailTest extends \Codeception\TestCase\Test
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



    public function testClass()
    {
        $email = new \Facilis\Users\UserEmail('test@seznam.cz');
        $this->assertEquals('test@seznam.cz', $email->getEmail());
    }



    public function testWrongEmail()
    {
        try {
            new \Facilis\Users\UserEmail('not_an_email');
        } catch (Exception $e) {
            $this->throwException($e);
        }
    }

}