<?php
// Here you can initialize variables that will be available to your tests

//require __DIR__ . "/../../vendor/autoload.php"; \Tracy\Debugger::enable();

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\Setup;


$paths = array(__DIR__ . "/../../src");
$isDevMode = true;

// the connection configuration
$dbParams = array(
    'driver' => 'pdo_sqlite',
    'user' => 'root',
    'password' => '',
    'memory' => true,
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$em = EntityManager::create($dbParams, $config);
\Codeception\Module\Doctrine2::$em = $em;

function buildSchema()
{
    $em = \Codeception\Module\Doctrine2::$em;
    $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
    $classes = array(
        $em->getClassMetadata('Facilis\Users\UserAggregate'),
        $em->getClassMetadata('Facilis\Users\UserData'),
        $em->getClassMetadata('Facilis\Users\OAuth2\OAuthAccount'),
    );
    $tool->createSchema($classes);
}