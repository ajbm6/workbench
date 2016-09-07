<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;


use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;

class Packagedescr implements IEnumerable
{
    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    const CONFIG = "packagedescr";

    private $command;
    private $requested;

    public function __construct(Workbench $command)
    {
        $this->command=$command;
        $this->requested=$this->command->workbenchSettings->requested;
    }

    public function read($silent)
    {

        /*if($silent && !$this->requested["packagdescr"]["valore-valido"] && !$this->requested["packagdescr"]["valore-valido-default"]){
            $this->exitWork("The description of package can't be void");
        }*/

        if($silent && !$this->requested["packagedescr"]["valore-valido"]){
            $this->requested["packagedescr"]["valore"] = "";
            $this->requested["packagedescr"]["valore-valido"]=true;
        }

        if($silent && !$this->requested["packagedescr"]["valore-valido"] && $this->requested["packagedescr"]["valore-valido-default"]){
            $this->requested["packagedescr"]["valore"]=$this->requested["packagedescr"]["valore-default"];
            $this->requested["packagedescr"]["valore-valido"]= true;
        }
        if(!$this->requested["packagedescr"]["valore-valido"]){
            $this->requested["packagedescr"]["valore"] = $this->command->ask('Description of package.');
            $this->requested["packagedescr"]["valore-valido"]= true;
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
