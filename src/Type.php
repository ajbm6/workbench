<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench;


class Type implements IEnumerable
{
    use Traits\Enumerable;

    const LARAVEL = "laravel";
    const NORMAL = "normal";

}