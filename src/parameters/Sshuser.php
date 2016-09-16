<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;


use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;
use Illuminate\Console\Command;

class Sshuser implements IEnumerable
{
    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    private $command;
    private $requested;

    const CONFIG = "ssh.user";

    public function __construct(Command $command)
    {
        $this->command=$command;
        $this->requested=$this->command->workbenchSettings->requested;
    }

    public function read($silent)
    {

        if($silent && !$this->requested["sshuser"]["valore-valido"] && !$this->requested["sshuser"]["valore-valido-default"]){
            $this->exitWork("The ssh user can't be void");
        }

        if($silent && !$this->requested["sshuser"]["valore-valido"] && $this->requested["sshuser"]["valore-valido-default"]){
            $this->requested["sshuser"]["valore"]=$this->requested["sshuser"]["valore-default"];
            $this->requested["sshuser"]["valore-valido"]= true;
        }
        if(!$this->requested["sshuser"]["valore-valido"]){
            $this->requested["sshuser"]["valore"] = $this->command->ask('SSH username');
            $this->requested["sshuser"]["valore-valido"]= true;
        }
        $this->command->getWorkbenchSettings()->setRequested($this->requested);
    }

    private function exitWork($error)
    {
        $this->command->error($error);
        exit();
    }

    public static function isValidValue($valore)
    {
        if(!isset($valore) || trim($valore)=="")
        {
            return false;
        }
        return true;
    }
}