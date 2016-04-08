<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench;


interface IEnumerable 
{
    public static function getCostants();
    public static function isValidValue($valore);
    public static function getCostantsValues($separator);

}