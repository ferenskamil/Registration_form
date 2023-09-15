<?php

function getClassNameFromPath(string $path) : string {
        $pathParts = explode(DIRECTORY_SEPARATOR , $path);
        return end($pathParts);
}

// spl_autoload_register(function($name) {
//         $name = str_replace(["/" , "\\"] , DIRECTORY_SEPARATOR , $name);
//         include "src" . DIRECTORY_SEPARATOR . "{$name}.php";
// });

// spl_autoload_register(function($name) {
//         $name = str_replace(["/" , "\\"] , DIRECTORY_SEPARATOR , $name);
//         include "helpers" . DIRECTORY_SEPARATOR . "{$name}.php";
// });

// spl_autoload_register(function($name) {
//         $name = str_replace(["/" , "\\"] , DIRECTORY_SEPARATOR , $name);
//         include "classes" . DIRECTORY_SEPARATOR . "{$name}.php";
// });

// function helpers_autoloader($function) : void {
//         include __DIR__ . '\\helpers\\' . $function . '.php';
// }
// spl_autoload_register('helpers_autoloader');

// spl_autoload_register(function($name) {
//         include "./classes/{$name}.php";
// });
spl_autoload_register(function($name) {
        $test = str_replace(["/" , "\\"], DIRECTORY_SEPARATOR, $name);
        $className = getClassNameFromPath($test);
        include "./classes/{$className}.php";
});

