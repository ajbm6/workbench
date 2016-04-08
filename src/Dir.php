<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench;

use Padosoft\Workbench\Traits\Enumerable;
use File;

class Dir implements IEnumerable
{
    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    public static function isValidValue($valore)
    {
        if(empty($valore)){
            $valore='%$&';
        }

        return !empty(File::glob($valore,GLOB_ONLYDIR ));
    }

    public static function adjustPath($path)
    {

        if (!isset($path) || $path == '') {
            return "";
        }

        return str_finish(str_replace('\\', '/', $path), '/');
    }
}

