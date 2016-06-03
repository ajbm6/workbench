<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;

use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;
use File;
use Config;



class Dirtype implements IEnumerable
{
    use Enumerable{
        Enumerable::isValidValue as isValidValueTrait;
    }

    const PUB = "public";
    const PRIV = "private";
    
    private $command;
    private $requested;

    public function __construct(Workbench $command)
    {
        $this->command=$command;
        $this->requested=$this->command->requested;
    }



    public function read($silent)
    {
        if($silent && !$this->requested["dirtype"]["valore-valido"] && !$this->requested["dirtype"]["valore-valido-default"]){
            $this->exitWork("The type of dir is not correct, choice from 'public' or 'private' ");
        }
        if($silent && !$this->requested["dirtype"]["valore-valido"] && $this->requested["dirtype"]["valore-valido-default"]){
            $this->requested["dirtype"]["valore-valido"] = $this->requested["dirtype"]["valore-valido-default"];
        }
        if(!$silent && !$this->requested["dirtype"]["valore-valido"]){
            $this->requested["dirtype"]["valore"] = $this->command->choice('What type of dir?', ['public', 'private']);
        }
        $dirtype= $this->requested["dirtype"]["valore"];
        if(substr($this->requested["type"]['valore'],-7) == 'package') {
            $this->requested["dir"]["valore"]=Dir::adjustPath(Config::get('workbench.dirtype.'.$dirtype.'.packages')).$this->requested["organization"]["valore"].'/';
        }        
        if(!substr($this->requested["type"]['valore'],-7) == 'package') {
            $this->requested["dir"]["valore"]=Dir::adjustPath(Config::get('workbench.dirtype.'.$dirtype.'.local'));
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
        return in_array($valore, self::getCostants(), null);
    }

}