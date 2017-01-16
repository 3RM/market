<?php

function __autoload($class_name) {
    //Папки, в которых хранятся наши классы
    $array_paths = array(
        '/models/',
        '/components/',
    );
    
    foreach($array_paths as $path){
        $path = ROOT . $path . $class_name . '.php';
        if(is_file($path)){
            require_once $path;
        }
    }
}
