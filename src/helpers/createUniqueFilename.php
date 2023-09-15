<?php

namespace App\Helpers;

function createUniqueFilename(string $prefix , string $oldFilename , string $extension) : string {
        $md5Part = substr(md5($oldFilename) , 0 , 6);
        return $prefix . "_" . $md5Part . "_" . time() . "." . $extension;
}

?>