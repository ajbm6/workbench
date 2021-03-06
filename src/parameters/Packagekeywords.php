<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;


use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;
use Illuminate\Console\Command;

class Packagekeywords implements IEnumerable
{
    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    const CONFIG = "packagekeywords";

    private $command;
    private $requested;

    public function __construct(Command $command)
    {
        $this->command=$command;
        $this->requested=$this->command->workbenchSettings->requested;
    }

    public function read($silent)
    {

        /*if($silent && !$this->requested["packagekeywords"]["valore-valido"] && !$this->requested["packagekeywords"]["valore-valido-default"]){
            $this->exitWork("The name of package can't be void");
        }*/

        if($silent && !$this->requested["packagekeywords"]["valore-valido"]){
            $this->requested["packagekeywords"]["valore"] = "";
            $this->requested["packagekeywords"]["valore-valido"]=true;
        }

        if($silent && !$this->requested["packagekeywords"]["valore-valido"] && $this->requested["packagekeywords"]["valore-valido-default"]){
            $this->requested["packagekeywords"]["valore"]=$this->requested["packagekeywords"]["valore-default"];
            $this->requested["packagekeywords"]["valore-valido"]= true;
        }
        if(!$this->requested["packagekeywords"]["valore-valido"]){
            $this->requested["packagekeywords"]["valore"] = $this->command->ask('Keywords of package, split by comma.');
            $this->requested["packagekeywords"]["valore-valido"]= true;
        }

        if($this->requested["packagekeywords"]["valore-valido"]){
            $this->requested["packagekeywords"]["valore"] = '"'. implode('","', explode(',',$this->requested["packagekeywords"]["valore"])).'"' ;
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
