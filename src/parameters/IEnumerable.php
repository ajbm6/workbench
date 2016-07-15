<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;



interface IEnumerable 
{
    public static function getCostants();
    public static function isValidValue($valore);
    public static function getCostantsValues($separator);
    public function read($silent);
    public static function getCostant($costant);
    

}