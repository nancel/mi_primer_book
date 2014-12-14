<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html', array());
})
->bind('homepage')
;

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});

$app->get('/book/{id}', function (Silex\Application $app, $id) use ($app) {
    $images = glob($app['upload_folder'] . '/' . $id . '/*');

    $images_path = array();

    foreach( $images as $img )
    {
        array_push($images_path, '/index_dev.php/book/img/' . $id . '/' . basename($img));    
    }

    return $app['twig']->render('book.twig', array(
        'images' => $images_path
    ));
});

$app->get('/book/img/{id}/{name}', function($id, $name, Request $request ) use ( $app ) {
    if ( !file_exists( $app['upload_folder'] . '/' . $id . '/' . $name ) )
    {
        throw new \Exception( 'File not found' );
    }

    $out = new BinaryFileResponse($app['upload_folder'] . '/' . $id . '/' . $name );

    return $out;
});
