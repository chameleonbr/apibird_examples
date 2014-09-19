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
    $result = $app->cache($app->request->getBody(), function($data) use ($app) {
        return $data;
    }, 20);
    return $app->response->ok($result);
});

$app->get('/cached', function() use ($app) {
    //produces or consumes calls to check if the client sends or expects extension
    $app->produces(['json', 'xml', 'form', 'text', 'html']);
    $result = $app->cache($app->request->getBody(), function($data) use ($app) {
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
});

$app->notFound(function()use ($app) {
    return $app->response->notFound(\ApiBird\JSend::error('Service not exist'));
});

try {
    $app->handle();
} catch (\Exception $e) {
    return $app->response->InternalServerError(\ApiBird\JSend::error($e->getMessage()));
}

