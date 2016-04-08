<?php
namespace Padosoft\Workbench;

/**
 * Class Git
 * @package Padosoft\Workbench
 */
class Git implements IEnumerable
{
    use Traits\Enumerable;

    const GITHUB = "github";
    const BITBUCKET = "bitbucket";
}