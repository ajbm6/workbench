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
    const CONFIG = "git.action";

    private $command;
    private $requested;

    public function __construct(Workbench $command)
    {
        $this->command=$command;
        $this->requested=$this->command->workbenchSettings->requested;
    }

    public function read($silent)
    {
        if($silent && !$this->requested["gitaction"]["valore-valido"] && !$this->requested["gitaction"]["valore-valido-default"]){
            $this->exitWork("The action for git is not correct, choice from 'push' or 'pull'");
        }
        if($silent && !$this->requested["gitaction"]["valore-valido"] && $this->requested["gitaction"]["valore-valido-default"]){
            $this->requested["gitaction"]["valore"]=$this->requested["gitaction"]["valore-default"];
            $this->requested["gitaction"]["valore-valido"]= true;
        }
        if(!$silent && !$this->requested["gitaction"]["valore-valido"]){
            $this->requested["gitaction"]["valore"] = $this->command->choice('What do you want do?', ['push', 'pull']);
            $this->requested["gitaction"]["valore-valido"]= true;
        }
        $this->command->workbenchSettings->requested=$this->requested;
    }

    private function exitWork($error)
    {
        $this->command->error($error);
        exit();
    }
    
}