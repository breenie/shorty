<?php

use Symfony\Component\HttpFoundation\Response;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->register(new \Shorty\Provider\ShortyServiceProvider());

$app->error(
    function (\Exception $e, $code) use ($app) {
        var_dump($app);exit;
        if ($app['debug']) {
            return;
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
