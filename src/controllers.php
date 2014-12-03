<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app['app.default_controller'] = $app->share(
    function () use ($app) {
        return new \Shorty\Controller\DefaultController($app['twig'], $app['logger']);
    }
);

$app->get('/', 'app.default_controller:indexAction')->bind('homepage');
//$app->get('/hello/{name}', 'app.default_controller:helloAction')->assert('hello', '\w+')->value('name', 'Anonymous coward');

$app->error(
    function (\Exception $e, $code) use ($app) {
        if ($app['debug']) {
            //return;
        }

        // 404.html.twig, or 40x.html, or 4xx.html.twig, or error.html
        $templates = array(
            'errors/' . $code . '.html.twig',
            'errors/' . substr($code, 0, 2) . 'x.html.twig',
            'errors/' . substr($code, 0, 1) . 'xx.html.twig',
            'errors/default.html.twig',
        );

        return new Response(
            $app['twig']->resolveTemplate($templates)->render(array('code' => $code, 'exception' => $e)),
            $code
        );
    }
);
