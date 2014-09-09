<?php

require '../bootstrap.php';

$app->get('/', function() use ($app) {
    //produces or consumes calls to check if the client sends expected extension
    $app->produces(['json', 'xml', 'html', 'form']);
    $return = ['xpto' => 123];
    //array returned is converted to Accept header extension
    return $app->response->ok($return);
});

$app->post('/', function() use ($app) {
    //produces or consumes calls to check if the client sends or expects extension
    $app->consumes(['json', 'xml', 'form', 'text'])->produces(['json', 'xml', 'form', 'text', 'html']);
    $result = $app->request->getBody();
    return $app->response->ok($result);
});

$app->post('/cached/{name}', function($name = '') use ($app) {
    //produces or consumes calls to check if the client sends or expects extension
    $app->consumes(['json', 'xml', 'form', 'text'])->produces(['json', 'xml', 'form', 'text', 'html']);
    $result = $app->serverCache($app->request->getBody(), function($data) use ($app) {
        return $data;
    }, 20);
    return $app->response->ok($result);
});

$app->get('/cached', function() use ($app) {
    //produces or consumes calls to check if the client sends or expects extension
    $app->produces(['json', 'xml', 'form', 'text', 'html']);
    $result = $app->serverCache($app->request->getBody(), function($data) use ($app) {
        return array('myresult' => 'ok');
    }, 15);
    return $app->response->ok($result);
});

$app->post('/all', function() use ($app) {
    //without consumes and/or produces, accept all registered types
    //$app->producesExcept(['yaml']);
    $return = $app->request->getBody();
    return $app->response->ok($result);
});

//Enable CORS

$app->options('(/.*)*', function() use ($app) {
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, HEAD, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
});

$app->notFound(function()use ($app) {
    return $app->response->notFound(\ApiBird\JSend::error('Service not exist'));
});

try {
    $app->handle();
} catch (\Exception $e) {
    return $app->response->InternalServerError(\ApiBird\JSend::error($e->getMessage()));
}

