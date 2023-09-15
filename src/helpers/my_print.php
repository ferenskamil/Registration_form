<?php

namespace App\Helpers;

function my_print(array | object | int | string $var)
{
        echo '<pre>' . print_r($var , true) . '</pre>';
}