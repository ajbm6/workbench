<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;

use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;


class GitAction implements IEnumerable
{
    use Enumerable;

    const PULL = "pull";
    const PUSH = "push";
 

    private $command;
    private $requested;

    public function __construct(Workbench $command)
    {
        $this->command=$command;
        $this->requested=$this->command->requested;
    }

    public function read($silent)
    {
        if($silent && !$this->requested["gitaction"]["valore-valido"] && !$this->requested["gitaction"]["valore-valido-default"]){
            $this->exitWork("The action for git is not correct, choice from 'push', 'pull' or 'force'");
        }
        if($silent && !$this->requested["gitaction"]["valore-valido"] && $this->requested["gitaction"]["valore-valido-default"]){
            $this->requested["gitaction"]["valore-valido"] = $this->requested["gitaction"]["valore-valido-default"];
        }
        if(!$silent && !$this->requested["gitaction"]["valore-valido"]){
            $this->requested["gitaction"]["valore"] = $this->command->choice('What do you want do?', ['push', 'pull']);
        }
        $this->command->requested=$this->requested;
    }

    private function exitWork($error)
    {
        $this->command->error($error);
        exit();
    }
    
}