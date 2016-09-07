<?php
namespace Padosoft\Workbench\Parameters;

use Padosoft\Workbench\Workbench;
use Padosoft\Workbench\Traits\Enumerable;

/**
 * Class Git
 * @package Padosoft\Workbench
 */
class Git implements IEnumerable
{
    use Enumerable;

    const GITHUB = "github";
    const BITBUCKET = "bitbucket";
    const CONFIG = "git.hosting";

    private $command;
    private $requested;

    public function __construct(Workbench $command)
    {
        $this->command=$command;
        $this->requested=$this->command->workbenchSettings->requested;
    }

    public function read($silent)
    {

        if($silent && !$this->requested["git"]["valore-valido"] && !$this->requested["git"]["valore-valido-default"]){
            $this->exitWork("Choice a git type, 'github', 'bitbucket' or ''.");
        }

        if($silent && !$this->requested["git"]["valore-valido"] && $this->requested["git"]["valore-valido-default"]){

            $this->requested["git"]["valore"]=$this->requested["git"]["valore-default"];
            $this->requested["git"]["valore-valido"]= true;
        }

        if(!$silent && !$this->requested["git"]["valore-valido"] && $this->command->confirm('Do you want add to git repository?')){
            $this->requested["git"]["valore"] = $this->command->choice('Github or Bitbucket?', ['github', 'bitbucket']);
            $this->requested["git"]["valore-valido"]=true;
        }
        
        $this->command->workbenchSettings->requested=$this->requested;
        return $this->requested["git"]["valore-valido"];

    }

    private function exitWork($error)
    {
        $this->command->error($error);
        exit();
    }
}