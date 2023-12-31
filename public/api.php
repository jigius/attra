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
                "api/0.0.1/contacts/{uuid}",
                (function (string $id) use (&$endpoints) {
                    $endpoints = $endpoints->with($id, new App\Api\Contacts\ConfiguredEndpoint());
                    return $id;
                }) ("CONTACTS")
            );
        $r
            ->addRoute(
                'DELETE',
                'api/0.0.1/contact/{uuid}',
                (function (string $id) use (&$endpoints) {
                    $endpoints = $endpoints->with($id, new App\Api\ContactDelete\ConfiguredEndpoint());
                    return $id;
                }) ("DELETE")
            );
        $r
            ->addRoute(
                "POST",
                "api/0.0.1/contact/{uuid}",
                (function (string $id) use (&$endpoints) {
                    $endpoints = $endpoints->with($id, new App\Api\ContactAdd\ConfiguredEndpoint());
                    return $id;
                }) ("ADD")
            );
});
$routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_GET["_route_"]);
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
    $respCode = 500;
}
http_response_code($respCode);
