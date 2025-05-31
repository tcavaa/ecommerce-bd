<?php

require_once __DIR__ . "/../vendor/autoload.php";

define("BASE_PATH", dirname(__DIR__) . "/");

Dotenv\Dotenv::createImmutable(BASE_PATH)->load();

handleCors();

function handleCors(): void
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
        http_response_code(200);
        exit();
    }
}

$dispatcher = FastRoute\simpleDispatcher(function (
    FastRoute\RouteCollector $r
) {
    $r->get("/graphql", [App\GraphQL\GraphQL::class, "handle"]);
    $r->post("/graphql", [App\GraphQL\GraphQL::class, "handle"]);
});

$uri = $_SERVER["REQUEST_URI"];

if (false !== ($pos = strpos($uri, "?"))) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($_SERVER["REQUEST_METHOD"], $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(["error" => "Not Found"]);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo json_encode(["error" => "Method Not Allowed"]);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        if (
            is_array($handler) &&
            class_exists($handler[0]) &&
            method_exists($handler[0], $handler[1])
        ) {
            $controller = new ($handler[0])();
            echo call_user_func([$controller, $handler[1]], $vars);
        } else {
            http_response_code(500);
            echo json_encode([
                "error" => "Internal Server Error: Invalid handler.",
            ]);
        }
        break;
}
