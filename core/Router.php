<?php
class Router {
    public static function dispatch() {
        $url = $_GET['url'] ?? '';
        $parts = array_values(array_filter(explode('/', $url)));

        $controller = $parts[0] ?? 'home';
        $action = $parts[1] ?? 'index';
        $params = array_slice($parts, 2);

        $controllerName = ucfirst($controller) . "Controller";
        $file = __DIR__ . "/../controllers/{$controllerName}.php";

        if (!file_exists($file)) die("Controller not found");
        require_once $file;

        $obj = new $controllerName();
        if (!method_exists($obj, $action)) die("Action not found");

        call_user_func_array([$obj, $action], $params);
    }
}
