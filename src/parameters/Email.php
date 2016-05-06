<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;


use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;
use Validator;
use Config;

class Email implements IEnumerable
{
    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    private $command;
    private $requested;

    public function __construct(Workbench $command)
    {
        $this->command=$command;
        $this->requested=$this->command->requested;
    }


    public function read($silent)
    {

        if($silent && !$this->requested["email"]["valore-valido"] && !$this->requested["email"]["valore-default-valido"]){
            $this->exitWork("Email is not correct.");
        }

        if($silent && !$this->requested["email"]["valore-valido"] && $this->requested["email"]["valore-default-valido"]){
            $this->requested["email"]["valore-valido"] = $this->requested["email"]["valore-default-valido"];
        }

        $attemps = Config::get('workbench.attemps');
        $attemp=0;

        while(!$silent && (!$this->requested["email"]["valore-valido"] || empty($this->requested["email"]["valore"])) && $attemp<$attemps){
            $this->command->error("This email '" .$this->requested["email"]["valore"]. "' is not valid");
            $this->requested["email"]["valore"] = $this->command->ask('The email associated to git repository',
                ($this->requested["email"]["valore-default-valido"]?$this->requested["email"]["valore-default"]:$this->requested["email"]["valore"]));
            $this->requested["email"]["valore-valido"] = Email::isValidValue($this->requested["email"]["valore"]);
            $attemp++;
            if ($attemp== $attemps) return $this->command->error("Exit for invalid email");
        }

        $this->command->requested=$this->requested;
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