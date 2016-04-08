<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench;


class GitAction implements IEnumerable
{
    use Traits\Enumerable;

    const PULL = "pull";
    const PUSH = "push";
    const FORCE = "force";
    
}