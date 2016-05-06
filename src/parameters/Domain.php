<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;


use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;

class Domain implements IEnumerable
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
        if($silent && !$this->requested["domain"]["valore-valido"]){
            $this->exitWork("Domain is not correct, specific a valid name.");
        }
        if(!$silent && !$this->requested["domain"]["valore-valido"]){
            $this->requested["domain"]["valore"] = $this->command->ask("What's the domain name?",$this->requested["domain"]["valore"]);
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

    public function deleteDomain()
    {

    }
}
