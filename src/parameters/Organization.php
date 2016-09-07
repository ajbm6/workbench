<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;


use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;

class Organization implements IEnumerable
{
    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    const CONFIG = "organization";

    private $command;
    private $requested;

    public function __construct(Workbench $command)
    {
        $this->command=$command;
        $this->requested=$this->command->workbenchSettings->requested;
    }

    public function read($silent)
    {
        if($silent && !$this->requested["organization"]["valore-valido"] && !$this->requested["organization"]["valore-valido-default"]){
            $this->exitWork("The organization for git can't be void");
        }

        if($silent && !$this->requested["organization"]["valore-valido"] && $this->requested["organization"]["valore-valido-default"]){
            $this->requested["organization"]["valore"]=$this->requested["organization"]["valore-default"];
            $this->requested["organization"]["valore-valido"]= true;
        }

        if(!$this->requested["organization"]["valore-valido"]){
            $this->requested["organization"]["valore"] = $this->command->ask('Vendor name and git repository\'s organization');
            $this->requested["organization"]["valore-valido"]= true;
        }
        $this->command->workbenchSettings->requested=$this->requested;
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