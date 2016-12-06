<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;


use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;
use Illuminate\Console\Command;
use phpseclib\Net\SSH2;

class Sshpassword implements IEnumerable
{
    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    private $command;
    private $requested;

    const CONFIG = "ssh.password";

    public function __construct(Command $command)
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

            if($this->requested["sshpassword"]["valore-valido-default"])
            {
                if($this->command->confirm("Do you want use ssh password in the config?","yes"))
                {
                    $this->requested["sshpassword"]["valore"] = $this->requested["sshpassword"]["valore-default"];
                    $this->requested["sshpassword"]["valore-valido"] = true;
                }
                else{
                    $this->requested["sshpassword"]["valore-valido"] = false;
                }

            }

            if(!$this->requested["sshpassword"]["valore-valido"]){
                $this->requested["sshpassword"]["valore"] = $this->command->secret('SSH password');


            }

            if(!$this->testPasswordSSH())
            {
                $this->command->error("Invalid SSH username or password!");
                exit();
            }


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

    public function testPasswordSSH()
    {
        $ssh = new SSH2($this->requested['sshhost']['valore']);
        return $ssh->login($this->requested['sshuser']['valore'], $this->requested['sshpassword']['valore']);
    }

}