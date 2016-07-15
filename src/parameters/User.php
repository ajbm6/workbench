<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;


use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;

class User implements IEnumerable
{
    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    const CONFIG = "git.user";

    private $command;
    private $requested;

    public function __construct(Workbench $command)
    {
        $this->command=$command;
        $this->requested=$this->command->requested;
    }

    public function read($silent)
    {

        if($silent && !$this->requested["user"]["valore-valido"] && !$this->requested["user"]["valore-valido-default"]){
            $this->exitWork("The user for git can't be void");
        }

        if($silent && !$this->requested["user"]["valore-valido"] && $this->requested["user"]["valore-valido-default"]){
            $this->requested["user"]["valore"]=$this->requested["user"]["valore-default"];
            $this->requested["user"]["valore-valido"]= true;
        }
        if(!$this->requested["user"]["valore-valido"]){
            $this->requested["user"]["valore"] = $this->command->ask('Git repository\'s username');
            $this->requested["user"]["valore-valido"]= true;
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
        if(!isset($valore) || trim($valore)=="")
        {
            return false;
        }
        return true;
    }
}
