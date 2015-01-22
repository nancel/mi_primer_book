<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\RememberMeServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use SimpleUser\UserServiceProvider;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new SecurityServiceProvider());
$app->register(new RememberMeServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new SwiftmailerServiceProvider());
$simpleUserProvider = new UserServiceProvider();
$app->register($simpleUserProvider);

$app['security.firewalls'] = array(
    // Ensure that the login page is accessible to all, if you set anonymous => false below.
    'login' => array(
        'pattern' => '^/user/login$',
    ),
    'register' => array(
        'pattern' => '^/user/register$',
    ),
    'secured_area' => array(
        'pattern' => '^/user/',
        'form' => array(
            'login_path' => '/user/login',
            'check_path' => '/user/login_check',
        ),
        'logout' => array(
            'logout_path' => '/user/logout',
        ),
        'users' => $app->share(function($app) { return $app['user.manager']; }),
    )
);

$app['swiftmailer.options'] = array();

$app['user.options'] = array(
    // ...
    'isUsernameRequired' => true,
    'templates' => array(
        'layout' => 'layout.twig',
        'login' => 'login.twig',
       /* 'register' => 'register.twig',
        'register-confirmation-sent' => 'register-confirmation-sent.twig',
        'login' => 'login.twig',
        'login-confirmation-needed' => 'login-confirmation-needed.twig',
        'forgot-password' => 'forgot-password.twig',
        'reset-password' => 'reset-password.twig',*/
        'view' => 'view.twig',
        'list' => 'list.twig',
         /* 'edit' => 'edit.twig',
        'list' => 'list.twig',*/
    ),
);

$app->mount('/user', $simpleUserProvider);

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

$app['upload_folder']=__DIR__ . '/../uploads';

return $app;
