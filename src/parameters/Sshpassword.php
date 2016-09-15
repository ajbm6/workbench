<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;


use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;

class Sshpassword implements IEnumerable
{
    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    private $command;
    private $requested;

    const CONFIG = "ssh.password";

    public function __construct(Workbench $command)
    {
        $this->command=$command;
        $this->requested=$this->command->workbenchSettings->requested;
    }

    public function read($silent)
    {

        if($silent && !$this->requested["sshpassword"]["valore-valido"] && !$this->requested["sshpassword"]["valore-valido-default"]){
            $this->exitWork("The ssh password can't be void");
        }

        if($silent && !$this->requested["sshpassword"]["valore-valido"] && $this->requested["sshpassword"]["valore-valido-default"]){
            $this->requested["sshpassword"]["valore"]=$this->requested["sshpassword"]["valore-default"];
            $this->requested["sshpassword"]["valore-valido"]= true;
        }

        if(!$silent && !$this->requested["sshpassword"]["valore-valido"]){
            $this->requested["sshpassword"]["valore"] = $this->command->secret('SSH password');
            $this->requested["sshpassword"]["valore-valido"]= true;
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