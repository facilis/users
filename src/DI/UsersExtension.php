<?php

namespace Facilis\Users\DI;

use Kdyby\Doctrine\DI\IEntityProvider;
use Nette\DI\CompilerExtension;

class UsersExtension extends CompilerExtension implements IEntityProvider
{

    public $defaults = [
        'oauth' => [
            'providers' => [],
            'redirect' => ''
        ]
    ];



    public function loadConfiguration()
    {
        $this->getContainerBuilder()->parameters = array_merge($this->getContainerBuilder()->parameters,
            ['facilis' => ['users' => $this->getConfig($this->defaults)]]);
        $this->compiler->parseServices($this->getContainerBuilder(), $this->loadFromFile(__DIR__ . '/services.neon'));
    }



    public function beforeCompile()
    {
        $this->getContainerBuilder()->removeDefinition('security.userStorage');
        $this->getContainerBuilder()->addDefinition('security.userStorage')
            ->setClass('Facilis\Users\Security\UserStorage');
    }



    /**
     * Returns associative array of Namespace => mapping definition
     *
     * @return array
     */
    function getEntityMappings()
    {
        return [
            'Facilis\Users' => __DIR__ . '/..'
        ];
    }


}