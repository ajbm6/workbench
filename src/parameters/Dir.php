<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench\Parameters;

use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;
use File;
use Config;

class Dir implements IEnumerable
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
        if($silent && !$this->requested["dir"]["valore-valido"] && !$this->requested["dir"]["valore-default-valido"]){
            $this->exitWork("Domain's path is not correct.");
        }

        if($silent && !$this->requested["dir"]["valore-valido"] && $this->requested["dir"]["valore-default-valido"]){
            $this->requested["dir"]["valore-valido"] = $this->requested["dir"]["valore-default-valido"];
        }

        $attemps = Config::get('workbench.attemps');
        $attemp=0;
        while(!$silent && !$this->requested["dir"]["valore-valido"] && $attemp<$attemps){
            $this->command->error("This domain path '" .$this->requested["dir"]["valore"]. "' is not valid");
            $this->requested["dir"]["valore"] = Dir::adjustPath($this->command->ask('Path dir for domain, without dir domain folder',
                ($this->requested["dir"]["valore-default-valido"]?$this->requested["dir"]["valore-default"]:$this->requested["dir"]["valore"])));
            $this->requested["dir"]["valore-valido"] = Dir::isValidValue($this->requested["dir"]["valore"]);
            $attemp++;
            if ($attemp== $attemps) return $this->command->error("Exit for invalid path");
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
        if(empty($valore)){
            $valore='%$&';
        }

        return !empty(File::glob($valore,GLOB_ONLYDIR ));
    }

    public static function adjustPath($path)
    {

        if (!isset($path) || $path == '') {
            return "";
        }

        return str_finish(str_replace('\\', '/', $path), '/');
    }
}

