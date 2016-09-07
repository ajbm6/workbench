<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 02/09/2016
 * Time: 12:55
 */

namespace Padosoft\Workbench;

use Illuminate\Console\Command;
use Config;

class WorkbenchSettings
{

    private $requested = array();
    private $command;

    const ACTION = "action";
    const DOMAIN = "domain";
    const TYPE = "type";
    const DIRTYPE = "dirtype";
    const DIR = "dir";
    const GIT = "git";
    const GITACTION = "gitaction";
    const GITHOOKENABLE = "githookenable";
    const USER = "user";
    const PASSWORD = "password";
    const EMAIL = "email";
    const ORGANIZATION = "organization";
    const SSHHOST = "sshhost";
    const SSHUSER = "sshuser";
    const SSHPASSWORD = "sshpassword";
    const PACKAGENAME = "packagename";
    const PACKAGEDESCR = "packagedescr";
    const PACKAGEKEYWORDS = "packagekeywords";

    public function __construct(Command $command){
        $this->command=$command;
    }

    public function prepare($val,$class)
    {
        $validVal=true;
        $validClass=false;
        $classwithnamespace = "Padosoft\\Workbench\\Parameters\\".ucfirst($class);
        $myclass=null;
        if(class_exists($classwithnamespace))
        {
            $validClass=true;
            $myclass=new $classwithnamespace($this->command);
            if(!$myclass::isValidValue($val))
            {
                $validVal=false;
            }
        }

        $emptyDefault=false;
        //$validDefault=false;
        $validDefault=false;

        $valDefault=Config::get('workbench.'.$myclass->getCostant("CONFIG"));

        if(!isset($valDefault) || $valDefault==="" ) {
            $emptyDefault=true;
        }

        if (!$emptyDefault && $validClass) {
            $validDefault=$myclass::isValidValue($valDefault);
        }

        $this->requested[$class] = [
            "valore"=>$val,
            "valore-valido"=>$validVal,
            "valore-default"=>$valDefault,
            "valore-valido-default"=>$validDefault,
            "valore-classe"=>$class,
            "valore-default-valida"=>$validClass];

        //return $this->requested[$class];

    }


    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }


}