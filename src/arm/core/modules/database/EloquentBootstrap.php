<?php

# Respect it not given! It's earned!!!!

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Pagination\Paginator;

class EloquentBootstrap
{
    /***
     * @var $capsule Capsule
     */
    protected static $capsule;

    public static function create(ARMMysqliConfigVO $configVO)
    {
        if (isset(self::$capsule)) return self::capsule();

        self::$capsule = new Capsule;

        self::$capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $configVO->getHost(),
            'database'  => $configVO->getDBName(),
            'username'  => $configVO->getUser(),
            'password'  => $configVO->getPassword(),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        self::$capsule->setEventDispatcher(new Dispatcher(new Container));
        self::$capsule->setAsGlobal();
        self::$capsule->bootEloquent();

		Paginator::currentPageResolver(function ($pageName = 'page') {
			$page = ARMNavigation::getVar($pageName);

			if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int) $page >= 1) {
				return $page;
			}

			return 1;
		});


        return self::capsule();

    }

    /***
     * @return Capsule
     */
    public static function capsule()
    {
        return self::$capsule;
    }
}
