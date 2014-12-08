<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
}));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
	'db.options'    => array(
		'driver'        => 'pdo_mysql',
		'host'          => 'localhost',
		'dbname'        => 'mi_primer_book',
		'user'          => 'root',
		'password'      => '',
		'charset'       => 'utf8',
		'driverOptions' => array(1002 => 'SET NAMES utf8',),
	),
));

$app->register(new Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider, array(
	"orm.em.options" => array(
		"mappings" => array(
			array(
				"type"      => "annotation",
				"namespace" => "Entity",
				"path"      => realpath(__DIR__."/Entity"),
			),
		),
	),
));

return $app;
