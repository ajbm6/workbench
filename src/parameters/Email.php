<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;


use Padosoft\Workbench\Workbench;
use Illuminate\Console\Command;
use Padosoft\Workbench\Traits\Enumerable;
use Validator;
use Config;

class Email implements IEnumerable
{
    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    const CONFIG = "git.email";

    private $command;
    private $requested;

    public function __construct(Command $command)
    {
        $this->command=$command;
        $this->requested=$this->command->workbenchSettings->requested;
    }


    public function read($silent)
    {

        if($silent && !$this->requested["email"]["valore-valido"] && !$this->requested["email"]["valore-valido-default"]){
            $this->exitWork("Email is not correct.");
        }

        if($silent && !$this->requested["email"]["valore-valido"] && $this->requested["email"]["valore-valido-default"]){
            $this->requested["email"]["valore"]=$this->requested["email"]["valore-default"];
            $this->requested["email"]["valore-valido"]= true;
        }

        $attemps = Config::get('workbench.attemps');
        $attemp=0;

        while(!$silent && (!$this->requested["email"]["valore-valido"] || empty($this->requested["email"]["valore"])) && $attemp<$attemps){
            $this->command->error("This email '" .$this->requested["email"]["valore"]. "' is not valid");

            $this->requested["email"]["valore"] = $this->command->ask('The email associated to git repository',
                ($this->requested["email"]["valore-valido-default"]?$this->requested["email"]["valore-default"]:$this->requested["email"]["valore"]));
            $this->requested["email"]["valore-valido"] = Email::isValidValue($this->requested["email"]["valore"]);
            $attemp++;
            if ($attemp== $attemps) return $this->command->error("Exit for invalid email");
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
        return Validator::make(
            [
                'email' => $valore
            ],
            [
                'email' => 'email'
            ]
        )->passes();

    }
}