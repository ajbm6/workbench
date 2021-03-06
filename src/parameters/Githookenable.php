<?php
namespace Padosoft\Workbench\Parameters;

use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;
use Illuminate\Console\Command;

/**
 * Class Git
 * @package Padosoft\Workbench
 */
class Githookenable implements IEnumerable
{
    use Enumerable {
        Enumerable::isValidValue as isValidValueTrait;
    }

    const CONFIG = "githookenable";

    private $command;
    private $requested;

    public function __construct(Command $command)
    {
        $this->command=$command;
        $this->requested=$this->command->workbenchSettings->requested;
    }

    public function read($silent)
    {

        if($silent && !$this->requested["githookenable"]["valore-valido"] && !$this->requested["githookenable"]["valore-valido-default"]){
            $this->requested["githookenable"]["valore"]=false;
            $this->requested["githookenable"]["valore-valido"]= true;
        }

        if($silent && !$this->requested["githookenable"]["valore-valido"] && $this->requested["githookenable"]["valore-valido-default"]){

            $this->requested["githookenable"]["valore"]=$this->requested["githookenable"]["valore-default"];
            $this->requested["githookenable"]["valore-valido"]= true;
        }

        if(!$silent && !$this->requested["githookenable"]["valore"] ){
            $this->requested["githookenable"]["valore"] = $this->command->confirm('Do you want add pre commit git hook?','yes');
            $this->requested["githookenable"]["valore-valido"]=true;
        }

        $this->command->getWorkbenchSettings()->setRequested($this->requested);
        return $this->requested["githookenable"]["valore-valido"];

    }

    public static function isValidValue($valore)
    {
        if(!isset($valore))
        {
            return false;
        }
        return true;
    }

    private function exitWork($error)
    {
        $this->command->error($error);
        exit();
    }
}