<?php
namespace Padosoft\Workbench;

/**
 * Class Action
 * @package Padosoft\Workbench
 */
class Action implements IEnumerable
{
    use Traits\Enumerable;

    const CREATE = "create";
    const DELETE = "delete";
}