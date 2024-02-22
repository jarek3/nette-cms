<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
        $router->addRoute('kontakt', 'Core:Contact:default');
        $router->addRoute('administrace', 'Core:Administration:default');

        $router->addRoute('<action>', [
            'presenter' => 'Core:Administration',
            'action' => [
                Route::FILTER_STRICT => true,
                Route::FILTER_TABLE => [
                    // řetězec v URL => akce presenteru
                    'administrace' => 'default',
                    'prihlaseni' => 'login',
                    'odhlasit' => 'logout',
                    'registrace' => 'register'
                ]
            ]
        ]);

        $router->addRoute('<action>[/<url>]', [
            'presenter' => 'Core:Article',
            'action' => [
                Route::FILTER_STRICT => true,
                Route::FILTER_TABLE => [
                    // řetězec v URL => akce presenteru
                    'seznam-clanku' => 'list',
                    'editor' => 'editor',
                    'odstranit' => 'remove'
                ]
            ]
        ]);

        $router->addRoute('[<url>]', 'Core:Article:default');
		return $router;
	}
}
