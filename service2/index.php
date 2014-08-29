<?php

require '../bootstrap.php';

$di->set('userService', function() {
    return new \Example\ExampleService();
});

$app->get('/users', function() use ($app) {
    $app->produces(['json', 'xml']);
    return $app->userService->listUsers();
});

$app->get('/users/{id}', function($id) use ($app) {
    $app->produces(['json', 'xml']);
    return $app->userService->getUser($id);
});

$app->post('/users/', function() use ($app) {
    $app->consumes(['json', 'xml', 'form']);
    $data = $app->request->getBody();
    $resourceId = $app->userService->saveUser($data);
    $app->response->setStatusCode(201,'Created');
    $app->response->setHeader();
    return $return;
});

$app->put('/users/{$id}', function($id) use ($app) {
    $app->produces(['json', 'xml']);
    $data = $app->request->getBody();
    return $app->userService->updateUser($id, $data);
});

$app->delete('/users/{id}', function($id) use ($app) {
    return $app->userService->deleteUser($id);
});
try {
    $app->handle();
} catch (\ApiBird\InvalidTypeException $e) {
    echo $e->getMessage();
} catch (\Exception $e) {
    echo $e->getMessage();
}