<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;


use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;

class Sshuser implements IEnumerable
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

        if($silent && !$this->requested["sshuser"]["valore-valido"] && !$this->requested["sshuser"]["valore-valido-default"]){
            $this->exitWork("The ssh user can't be void");
        }

        if($silent && !$this->requested["sshuser"]["valore-valido"] && $this->requested["sshuser"]["valore-valido-default"]){
            $this->requested["sshuser"]["valore-valido"] = $this->requested["sshuser"]["valore-valido-default"];
        }
        if(!$this->requested["sshuser"]["valore-valido"]){
            $this->requested["sshuser"]["valore"] = $this->command->ask('SSH username');
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