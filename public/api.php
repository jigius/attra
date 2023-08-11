<?php

use Local\App;
use Local\Illuminate as I;

require_once __DIR__ .  '/../vendor/autoload.php';

$cfg = new App\AppCfg();
$endpoints = new App\Api\VanillaEndpoints();
$dispatcher =
    FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use (&$endpoints) {
        $r
            ->addRoute(
                "GET",
                "api/contacts/{uuid}",
                (function (string $id) use (&$endpoints) {
                    $endpoints = $endpoints->with($id, new App\Api\Contacts\ConfiguredEndpoint());
                    return $id;
                }) ("CONTACTS")
            );
        $r
            ->addRoute(
                'DELETE',
                'api/contact/{uuid}',
                (function (string $id) use (&$endpoints) {
                    $endpoints = $endpoints->with($id, new App\Api\ContactDelete\ConfiguredEndpoint());
                    return $id;
                }) ("DELETE")
            );
        $r
            ->addRoute(
                "PUT",
                "api/contact/{uuid}",
                (function (string $id) use (&$endpoints) {
                    $endpoints = $endpoints->with($id, new App\Api\ContactAdd\ConfiguredEndpoint());
                    return $id;
                }) ("ADD")
            );
});


// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_GET["_route_"];

//echo $httpMethod;

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
$respCode = 200;
try {
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            echo "not found!";
            $respCode = 404;
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            $respCode = 405;
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            //$respCode = 200;
            //var_dump($handler);
            //var_dump($vars);
            $rq = new App\Api\ConfiguredRequest($vars);
            $endpoints
                ->endpoint($handler)
                ->withRepository(
                    new App\Persistence\RepositoryConfigured($cfg)
                )
                ->processed(new App\Api\ConfiguredRequest($vars), new I\Api\VanillaResponse())
                ->output();
            break;
    }
} catch (DomainException $ex) {
    if ($ex->getCode() == 404) {
        $respCode = 404;
    }
} catch (InvalidArgumentException $ex) {
    if ($ex->getCode() == 400) {
        echo $ex->getMessage();
        $respCode = 400;
    }
} catch (Throwable $ex) {
    var_dump($ex);
    $respCode = 500;
}

http_response_code($respCode);
