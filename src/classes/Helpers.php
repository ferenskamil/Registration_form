<?php

namespace App\Classes;

class Helpers {

        public static function createUniqueFilename(string $prefix , string $oldFilename , string $extension) : string {
                $md5Part = substr(md5($oldFilename) , 0 , 6);
                return $prefix . "_" . $md5Part . "_" . time() . "." . $extension;
        }

        public static function myPrint(mixed  $var) : void {
                echo '<pre>' . print_r($var , true) . '</pre>';
        }
}

?>