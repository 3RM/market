<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Router
 *
 * @author rodnoy
 */
class Router {

    //Массив, в котором хранятся маршруты
    private $routes;

    public function __construct() {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);
    }

    /**
     * Returns request string
     * @return string
     */
    private function getURI() {

        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    //Принимает управление от front controller
    public function run() {

        //Получаем строку запроса
        $uri = $this->getURI();

        //Проверить наличие такого запроса в routes.php
        foreach ($this->routes as $uriPattern => $path) {
            //Сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)) {
                
                //Получаем внутренний путь из внешнего согласно правилу
                $internalRoute = preg_replace("~$uriPattern~",$path, $uri);
                //Определить controller, action, params
                $segments = explode('/', $internalRoute);

                $controllerName = array_shift($segments) . 'Controller';
                $controllerName = ucfirst($controllerName);

                $actionName = 'action' . ucfirst(array_shift($segments));
                
                $parameters = $segments;                
                
                //Подключить файл класса-контроллера
                $controllerFile = ROOT . '/controllers/' .
                        $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    include_once ($controllerFile);
                }

                //Создать обьект, вызвать action
                $controllerObject = new $controllerName;
                //$result = $controllerObject->$actionName($parameters);
                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                
                //Если action существует,
                //то обрываем foreach (поиск контроллеров и методов)
                if ($result != null) {
                    break;
                }
            }
        }
    }

}
