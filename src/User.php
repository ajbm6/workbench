<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench;

use Padosoft\Workbench\Traits\Enumerable;

class User implements IEnumerable
{
    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    public static function isValidValue($valore)
    {
        if(!isset($valore) || trim($valore)=="")
        {
            return false;
        }
        return true;
    }
}
