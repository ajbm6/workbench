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

    private $command;
    private $requested;

    public function __construct(Workbench $command)
    {
        $this->command=$command;
        $this->requested=$this->command->requested;
    }

    public function read($silent)
    {
        if($silent && empty($this->requested["git"]["valore-valido"])) {
            $this->requested["git"]["valore-valido"]= $this->requested["git"]["valore-valido-default"];
        }

        if($silent && !$this->requested["git"]["valore-valido"] && !$this->requested["git"]["valore-valido-default"] && !empty($this->requested["git"]["valore-valido"])){
            $this->exitWork("Choice a git type, 'github', 'bitbucket' or ''.");
        }

        if($silent && !$this->requested["git"]["valore-valido"] && $this->requested["git"]["valore-valido-default"] && !empty($this->requested["git"]["valore-valido"])){
            $this->requested["git"]["valore-valido"] = $this->requested["git"]["valore-valido-default"];
        }

        if(!$silent && !$this->requested["git"]["valore-valido"] && $this->command->confirm('Do you want add to git repository?')){
            $this->requested["git"]["valore"] = $this->command->choice('Github or Bitbucket?', ['github', 'bitbucket']);
            $this->requested["git"]["valore-valido"]=true;
        }
        
        $this->command->requested=$this->requested;
        return $this->requested["git"]["valore-valido"];

    }

    private function exitWork($error)
    {
        $this->command->error($error);
        exit();
    }
}