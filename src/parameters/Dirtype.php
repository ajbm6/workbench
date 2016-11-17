<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;

use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;
use Illuminate\Console\Command;
use File;
use Config;



class Dirtype implements IEnumerable
{
    use Enumerable{
        Enumerable::isValidValue as isValidValueTrait;
    }

    const PUB = "public";
    const PRIV = "private";
    const CONFIG = "dirtype";

    private $command;
    private $requested;

    public function __construct(Command $command)
    {
        $this->command=$command;
        $this->requested=$this->command->workbenchSettings->requested;
    }



    public function read($silent)
    {
        if($silent && !$this->requested["dirtype"]["valore-valido"] && !$this->requested["dirtype"]["valore-valido-default"]){
            $this->exitWork("The type of dir is not correct, choice from 'public' or 'private' ");
        }
        if($silent && !$this->requested["dirtype"]["valore-valido"] && $this->requested["dirtype"]["valore-valido-default"]){

            $this->requested["dirtype"]["valore"]=$this->requested["dirtype"]["valore-default"];
            $this->requested["dirtype"]["valore-valido"]= true;
        }
        if(!$silent && !$this->requested["dirtype"]["valore-valido"]){
            $this->requested["dirtype"]["valore"] = $this->command->choice('What type of dir?', ['public', 'private'], 0);
            $this->requested["dirtype"]["valore-valido"]= true;
        }
        $dirtype= $this->requested["dirtype"]["valore"];
        if(substr($this->requested["type"]['valore'],-7) == 'package') {
            $this->requested["dir"]["valore"]=Dir::adjustPath(Config::get('workbench.diraccess.'.$dirtype.'.packages')).$this->requested["organization"]["valore"].'/';
        }        
        if(substr($this->requested["type"]['valore'],-7) != 'package') {
            $this->requested["dir"]["valore"]=Dir::adjustPath(Config::get('workbench.diraccess.'.$dirtype.'.local'));
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
        return in_array($valore, self::getCostants(), null);
    }

}