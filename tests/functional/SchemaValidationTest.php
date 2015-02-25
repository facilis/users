<?php


class SchemaValidationTest extends \Codeception\TestCase\Test
{
    /**
     * @var \FunctionalTester
     */
    protected $tester;



    protected function _before()
    {
    }



    protected function _after()
    {
    }



    // tests
    public function testSchemaValidity()
    {
        $em = \Codeception\Module\Doctrine2::$em;
        $schema = new \Doctrine\ORM\Tools\SchemaValidator($em);
        $errors = $schema->validateMapping();
        $valid = count($errors) == 0;
        if (!$valid) {
            \Codeception\Util\Debug::debug($errors);
        }
        $this->assertEquals(true, $valid);
    }

}