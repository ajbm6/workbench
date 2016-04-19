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
            $this->exitWork("Type is not correct, choice from 'laravel' or 'normal'");
        }
        if($silent && !$this->requested["type"]["valore-valido"] && $this->requested["type"]["valore-valido-default"]){
            $this->requested["type"]["valore-valido"] = $this->requested["type"]["valore-valido-default"];
        }
        if(!$silent && !$this->requested["type"]["valore-valido"]){
            $this->requested["type"]["valore"] = $this->command->choice('Project type?', ['laravel', 'normal']);
        }
        $this->command->requested=$this->requested;
    }

    private function exitWork($error)
    {
        $this->command->error($error);
        exit();
    }

}