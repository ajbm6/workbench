<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;

use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;
use Illuminate\Console\Command;
use Validator;
use Config;

class Sshhost
{
    private $command;
    private $requested;

    const CONFIG = "ssh.server";

    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    public function __construct(Command $command)
    {
        $this->command=$command;
        $this->requested=$this->command->workbenchSettings->requested;
    }

    public function read($silent)
    {
        if($silent && !$this->requested["sshhost"]["valore-valido"] && !$this->requested["sshhost"]["valore-valido-default"]){
            $this->exitWork("SSH host is not correct, specific a valid name.");
        }
        if($silent && !$this->requested["sshhost"]["valore-valido"] && $this->requested["sshhost"]["valore-valido-default"]){
            $this->requested["sshhost"]["valore"]=$this->requested["sshhost"]["valore-default"];
            $this->requested["sshhost"]["valore-valido"]= true;
        }

        $attemps = Config::get('workbench.attemps');
        $attemp=0;

        if(!$silent && !$this->requested["sshhost"]["valore-valido"] && !$this->command->confirm('Do you want use ssh to '.$this->requested["action"]["valore"].' virtualhost?')) {
            return false;
        }

        do {

            $this->requested["sshhost"]["valore"] = $this->command->ask('SSH host IP',
                ($this->requested["sshhost"]["valore-valido-default"]?$this->requested["sshhost"]["valore-default"]:$this->requested["sshhost"]["valore"]));
            $this->requested["sshhost"]["valore-valido"] = Sshhost::isValidValue($this->requested["sshhost"]["valore"]);
            if(!$this->requested["sshhost"]["valore-valido"]) {
                $this->command->error("This host '" .$this->requested["sshhost"]["valore"]. "' is not valid");
            }
            $attemp++;
            if ($attemp== $attemps) return $this->command->error("Exit for invalid host");
        } while(!$silent && (!$this->requested["sshhost"]["valore-valido"] || empty($this->requested["sshhost"]["valore"])) && $attemp<$attemps);


        $this->command->getWorkbenchSettings()->setRequested($this->requested);
        return $this->requested["sshhost"]["valore-valido"];

    }

    private function exitWork($error)
    {
        $this->command->error($error);
        exit();
    }

    public static function isValidValue($valore)
    {
        //$regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        if(!isset($valore) || empty($valore)){
            return false;
        }

        return Validator::make(
            [
                'host' => $valore,
            ],
            [
                'host' => 'ip',
            ]
        )->passes();
    }


}