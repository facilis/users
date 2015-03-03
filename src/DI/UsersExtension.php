<?php

namespace Facilis\Users\DI;

use Nette\DI\CompilerExtension;

class UsersExtension extends CompilerExtension
{
    public function loadConfiguration()
    {
        $this->compiler->parseServices($this->getContainerBuilder(), $this->loadFromFile(__DIR__ . '/services.neon'));
    }



    public function beforeCompile()
    {
        $this->getContainerBuilder()->getDefinition('security.userStorage')
            ->setClass('Facilis\Users\Security\UserStorage');
    }


}