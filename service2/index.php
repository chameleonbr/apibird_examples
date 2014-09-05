<?php

require '../bootstrap.php';

/**
 * @package
 * @category
 *
 * @SWG\Resource(
 *   apiVersion="1.0.0",
 *   swaggerVersion="1.2",
 *   basePath="http://localhost/apibird_examples/service2",
 *   resourcePath="/users",
 *   description="Operations about user",
 *   @SWG\Produces("application/json"),
 *   @SWG\Produces("application/xml"),
 *   @SWG\Consumes("application/xml"),
 *   @SWG\Consumes("application/json"),
 *   @SWG\Consumes("application/form-urlencoded")
 * )
 */
$di->set('userService', function() {
    return new \Example\ExampleService();
});

/**
 * @SWG\Api(path="/users",
 *   @SWG\Operation(
 *     method="GET",
 *     summary="Get users",
 *     notes="",
 *     type="User",
 *     nickname="getUsers",
 *     @SWG\ResponseMessage(code=404, message="Users not found")
 *   ),
 *   @SWG\Operation(
 *     method="POST",
 *     summary="Creates user",
 *     notes="",
 *     type="void",
 *     nickname="createUser",
 *     @SWG\Parameter(
 *       name="body",
 *       description="User object",
 *       required=true,
 *       type="User",
 *       paramType="body"
 *     )
 *   )
 * )
 */
$app->get('/users', function() use ($app) {
    $app->produces(['json', 'xml']);
    return $app->response->ok($app->userService->listUsers());
});
$app->post('/users', function() use ($app) {
    $app->consumes(['json', 'xml', 'form']);
    try {
        $data = $app->request->getBody();
        $resource = $app->userService->saveUser($data);
        return $app->response->created(\ApiBird\JSend::success('User saved'), ['Location' => '/users/' . $resource[0]]);
    } catch (\Exception $e) {
        return $app->response->badRequest(\ApiBird\JSend::error('User could not be saved'));
    }
});
/**
 * @SWG\Api(path="/user/{id}",
 *   @SWG\Operation(
 *     method="GET",
 *     summary="Get user by user id",
 *     notes="",
 *     type="User",
 *     nickname="getUserById",
 *     @SWG\Parameter(
 *       name="id",
 *       description="The id that needs to be fetched. Use user for testing.",
 *       required=true,
 *       type="integer",
 *       paramType="path"
 *     ),
 *     @SWG\ResponseMessage(code=400, message="Invalid username supplied"),
 *     @SWG\ResponseMessage(code=404, message="User not found")
 *   ),
 * @SWG\Operation(
 *     method="PUT",
 *     summary="Put by user id",
 *     notes="",
 *     type="void",
 *     nickname="putUserById",
 *     @SWG\Parameter(
 *       name="id",
 *       description="The id that needs to be updated. Use 1 for testing.",
 *       required=true,
 *       type="integer",
 *       paramType="path"
 *     ),
 *     @SWG\Parameter(
 *       name="body",
 *       description="User object",
 *       required=true,
 *       type="User",
 *       paramType="body"
 *     ),
 *     @SWG\ResponseMessage(code=400, message="Invalid username supplied"),
 *     @SWG\ResponseMessage(code=404, message="User not found")
 *   ),
 * @SWG\Operation(
 *     method="DELETE",
 *     summary="Delete by user id",
 *     notes="",
 *     type="void",
 *     nickname="delUserById",
 *     @SWG\Parameter(
 *       name="id",
 *       description="The id that needs to be deleted. Use 1 for testing.",
 *       required=true,
 *       type="integer",
 *       paramType="path"
 *     ),
 *     @SWG\ResponseMessage(code=400, message="Invalid username supplied"),
 *     @SWG\ResponseMessage(code=404, message="User not found")
 *   )
 * )
 */
$app->get('/users/{id}', function($id) use ($app) {
    $app->produces(['json', 'xml']);
    return $app->response->ok($app->userService->getUser($id));
});

$app->put('/users/{id}', function($id) use ($app) {
    $app->produces(['json', 'xml']);
    $data = $app->request->getBody();
    $ok = $app->userService->updateUser($id, $data);
    if ($ok) {
        return $app->response->ok(\ApiBird\JSend::success('User updated'));
    } else {
        return $app->response->badRequest(\ApiBird\JSend::error('User not exist or could not be modified'));
    }
});
$app->delete('/users/{id}', function($id) use ($app) {
    if ($app->userService->deleteUser($id)) {
        return $app->response->ok(\ApiBird\JSend::success('User deleted'));
    } else {
        return $app->response->notFound(\ApiBird\JSend::error('User not exist'));
    }
});

$app->notFound(function()use ($app) {
    return $app->response->notFound(\ApiBird\JSend::error('Service not exist'));
});
try {
    $app->handle();
} catch (\Exception $e) {
    return $app->response->notFound(\ApiBird\JSend::error($e->getMessage()));
}