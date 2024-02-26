<?php
namespace App\Model;

use Nette;
class AuthorizatorFactory
{
    /**
     * @return Nette\Security\Permission
     */
    public static function create()
    {
        $acl = new Nette\Security\Permission;

        // pokud chceme, můžeme role a zdroje načíst z databáze
        $acl->addRole('admin', 'editor');
        $acl->addRole('guest');
        $acl->addRole('editor');

        $acl->addResource('backend');

        $acl->allow('admin', 'backend');
        $acl->deny('guest', 'backend');

        // případ A: role admin má menší váhu než role guest
        $acl->addRole('john', ['admin', 'guest']);
        $acl->isAllowed('john', 'backend'); // false

        // případ B: role admin má větší váhu než guest
        $acl->addRole('mary', ['guest', 'admin']);
        $acl->isAllowed('mary', 'backend'); // true

        return $acl;
    }
}