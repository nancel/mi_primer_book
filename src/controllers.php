<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Entity\Book;

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

$app->get('user/books', function () use ($app){

    $entity_manager = $app["orm.em"];

    if ($app['security']->isGranted('ROLE_ADMIN')) {
        $books = $entity_manager->getRepository("Entity\Book")->findAll();
    }
    else {
        $token = $app['security']->getToken();
        $user = $token->getUser();

        $books = $entity_manager->getRepository("Entity\Book")->findBy(array('user_id' => $user->getId()));
    }    

    return $app['twig']->render('books.twig', array("books"=>$books));
})
->bind('books')
;

$app->get('user/book/{id}', function (Silex\Application $app, $id) use ($app) {
    $images = glob($app['upload_folder'] . '/' . $id . '/*');

    $images_data = array();

    foreach( $images as $img )
    {
        $image = array(
            'path' => '/user/book/img/' . $id . '/' . basename($img),
            'name' => basename($img)
        );

        array_push($images_data, $image);    
    }

    return $app['twig']->render('book.twig', array(
        'images' => $images_data
    ));
})
->bind('book')
;

$app->post('user/bookadd', function (Request $request) use ($app) {

    $book = new Book();
    $book->name = $request->get('name');
    $book->path = $request->get('path');
    $book->user_id = $request->get('userid');

    $entity_manager = $app["orm.em"];
    $entity_manager->persist($book);
    $entity_manager->flush();

    return $app->redirect('/user/books');
});

$app->get('user/bookadd', function () use ($app){    
    $user_manager = $app['user.manager'];

    $users = $user_manager->findBy(array(), array(
            'order_by' => array('name', 'ASC'),
    ));  

    return $app['twig']->render('book_add.twig', array(
        'error' => '',
        'users' => $users
    ));
})
->bind('book_add')
;

$app->post('user/useradd', function (Request $request) use ($app) {

    $user_manager = $app['user.manager'];
    
    $user = $user_manager->createUser(
        $request->request->get('email'),
        $request->request->get('password'),
        $request->request->get('name') ?: null
    );

    if ($username = $request->request->get('username')) {
        $user->setUsername($username);
    }
    
    $user_manager->insert($user);

    return $app->redirect('/user/list');
});

$app->get('user/useradd', function () use ($app){ 
 
    return $app['twig']->render('user_add.twig', array(
        'error' => ''
    ));
})
->bind('user_add')
;

$app->get('user/book/img/{id}/{name}', function($id, $name, Request $request ) use ( $app ) {
    if ( !file_exists( $app['upload_folder'] . '/' . $id . '/' . $name ) )
    {
        throw new \Exception( 'File not found' );
    }

    $out = new BinaryFileResponse($app['upload_folder'] . '/' . $id . '/' . $name );

    return $out;
});


