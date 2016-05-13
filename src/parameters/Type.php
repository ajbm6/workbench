<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;

use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;

class Type implements IEnumerable
{
    use Enumerable;

    const LARAVEL = "laravel";
    const NORMAL = "normal";
    const LARAVEL_PACKAGE = "laravel_package";
    const AGNOSTIC_PACKAGE = "agnostic_package";

    private $command;
    private $requested;

    public function __construct(Workbench $command)
    {
        $this->command=$command;
        $this->requested=$this->command->requested;
    }

    public function read($silent)
    {
        if($silent && !$this->requested["type"]["valore-valido"] && !$this->requested["type"]["valore-valido-default"]){
            $this->exitWork("Type is not correct, choice from 'laravel', 'normal', 'laravel_package' or 'agnostic_package'");
        }
        if($silent && !$this->requested["type"]["valore-valido"] && $this->requested["type"]["valore-valido-default"]){
            $this->requested["type"]["valore-valido"] = $this->requested["type"]["valore-valido-default"];
        }
        if(!$silent && !$this->requested["type"]["valore-valido"]){
            $this->requested["type"]["valore"] = $this->command->choice('Project type?', ['laravel', 'normal','laravel_package','agnostic_package']);
        }
        $this->command->requested=$this->requested;
    }

    private function exitWork($error)
    {
        $this->command->error($error);
        exit();
    }

}