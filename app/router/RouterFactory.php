<?php

namespace App;

use Nette,
    Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route,
    Nette\Application\Routers\SimpleRouter;

/**
 * Router factory.
 */
class RouterFactory {

    /**
     * @return \Nette\Application\IRouter
     */
    public function createRouter() {
        $router = new RouteList();

        if (PHP_SAPI === 'cli') {
            $router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
        } else {
            $router[] = new Route('admin/<presenter>/<action>/<id>', array(
                'module' => 'Admin',
                'presenter' => 'Homepage',
                'action' => 'default',
                'id' => NULL,
            ));

            $router[] = new Route('<presenter>/<action>/<id>', array(
                'module' => 'Front',
                'presenter' => 'Homepage',
                'action' => 'default',
                'id' => NULL,
            ));

            $router[] = new Route('index.php', array(
                'module' => 'Front',
                'presenter' => 'Homepage',
                'action' => 'default'
                    ), Route::ONE_WAY);
        }
        return $router;
    }

}
