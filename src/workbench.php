<?php

namespace Padosoft\Workbench;

use Illuminate\Console\Command;
use Config;
use GrahamCampbell\GitHub\Facades\GitHub;

class Workbench extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workbench:new
                            {action? : create or delete}
                            {domain? : domain name}
                            {--t|type= : laravel or normal}
                            {--d|dir= : project dir}
                            {--g|git= : github or bitbucket}
                            {--a|gitaction= : push, pull or force}
                            {--u|user= : git user}
                            {--p|password= : git password}
                            {--e|email= : git email}
                            {--o|organization= : organization in github or bitbucket}
                            {--s|silent : no questions}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = <<<EOF
The <info>workbench:new</info> ....
EOF;

    protected $requested = array();

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->hardWork($this->argument(), $this->option());
    }

    /**
     * @param $argument
     * @param $option
     */
    private function hardWork($argument, $option)
    {
        //$tuttoOk = true;
        $silent=$option["silent"];
        $this->validate($argument, $option);


        $this->readAction($silent);
        $this->readDomain($silent);
        if($this->requested["action"]["valore"]=="delete"){
            $this->deleteDomain();
            return;
        }
        $this->readType($silent);
        $this->readDir($silent);
        if($this->readGit($silent)){
            $this->readGitaction($silent);
            $this->readUser($silent);
            $this->readPassword($silent);
            $this->readEmail($silent);
            $this->readOrganization($silent);
        }

        //$this->notifyResult( $tuttoOk);

    }

    /**
     * @param $tuttoOk
     */
    private function notifyResult($tuttoOk)
    {
        if ($tuttoOk) {
            return $this->notifyOK();
        }
        $this->notifyKO();
    }


    private function notifyOK()
    {
        $esito = "";
        $this->line($esito);
    }

    private function notifyKO()
    {
        $esito = "";
        $this->error($esito);
    }


    private function prepare($val,$class)
    {
        $validVal=true;
        $validClass=false;
        $classwithnamespace = "Padosoft\\Workbench\\".ucfirst($class);
        $myclass=null;
        if(class_exists($classwithnamespace))
        {
            $validClass=true;
            $myclass=new $classwithnamespace();
            if(!$myclass::isValidValue($val))
            {
                $validVal=false;
            }
        }

        $emptyDefault=false;
        $validDefault=false;
        $valDefault=Config::get('workbench.'.$class);

        if(!isset($valDefault) || $valDefault=="") {
            $emptyDefault=true;
        }

        if (!$emptyDefault && $validClass) {
            $validDefault=$myclass::isValidValue($valDefault);
        }

        return array(
            "valore"=>$val,
            "valore-valido"=>$validVal,
            "valore-default"=>$valDefault,
            "valore-default-valido"=>$validDefault,
            "valore-classe"=>$class,
            "valore-default-valida"=>$validClass);

    }

    private function validate($argument, $option)
    {
        /*foreach ($argument as $key => $value) {
            $requested[$key] = $this->prepare($value,"Padosoft\\Workbench\\".ucfirst($key));
        }

        foreach ($option as $key => $value) {
            $requested[$key] = $this->prepare($value,"Padosoft\\Workbench\\".ucfirst($key));
        }*/

        $this->requested["action"] = $this->prepare($argument["action"],"action");
        $this->requested["domain"] = $this->prepare($argument["domain"],"domain");
        $this->requested["type"] = $this->prepare($option["type"],"type");
        $this->requested["dir"] = $this->prepare(Dir::adjustPath($option["dir"]),"dir");
        $this->requested["git"] = $this->prepare($option["git"],"git");
        $this->requested["gitaction"] = $this->prepare($option["gitaction"],"gitaction");
        $this->requested["user"] = $this->prepare($option["user"],"user");
        $this->requested["password"] = $this->prepare($option["password"],"password");
        $this->requested["email"] = $this->prepare($option["email"],"email");
        $this->requested["organization"] = $this->prepare($option["organization"],"organization");

    }

    private function readAction($silent)
    {
        if($silent && !$this->requested["action"]["valore-valido"] && !$this->requested["action"]["valore-default-valido"]){
            $this->exitWork("Action is not correct, choice from 'create' or 'delete'");
        }

        if($silent && !$this->requested["action"]["valore-valido"] && $this->requested["action"]["valore-default-valido"]){
            $this->requested["action"]["valore-valido"]= $this->requested["action"]["valore-default-valido"];
        }

        if(!$silent && !$this->requested["action"]["valore-valido"]){
            $this->requested["action"]["valore"] = $this->choice('What do you want to do?', ['create', 'delete']);
        }
    }

    private function readDir($silent)
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
            $this->error("This domain path '" .$this->requested["dir"]["valore"]. "' is not valid");
            $this->requested["dir"]["valore"] = Dir::adjustPath($this->ask('Path for domain', 
                ($this->requested["dir"]["valore-default-valido"]?$this->requested["dir"]["valore-default"]:$this->requested["dir"]["valore"])));
            $this->requested["dir"]["valore-valido"] = Dir::isValidValue($this->requested["dir"]["valore"]);
            $attemp++;
            if ($attemp== $attemps) return $this->error("Exit for invalid path");
        }
    }

    private function readDomain($silent)
    {
        if(silent && !$this->requested["domain"]["valore-valido"]){
            $this->exitWork("Domain is not correct, specific a valid name.");
        }
        if(!silent && !$this->requested["domain"]["valore-valido"]){
            $this->requested["domain"]["valore"] = $this->ask("What's the domain name?",$this->requested["domain"]["valore"]);
        }

    }

    private function readEmail($silent)
    {

        if($silent && !$this->requested["email"]["valore-valido"] && !$this->requested["email"]["valore-default-valido"]){
            $this->exitWork("Email is not correct.");
        }

        if($silent && !$this->requested["email"]["valore-valido"] && $this->requested["email"]["valore-default-valido"]){
            $this->requested["email"]["valore-valido"] = $this->requested["email"]["valore-default-valido"];
        }

        $attemps = Config::get('workbench.attemps');
        $attemp=0;

        while(!$silent && (!$this->requested["email"]["valore-valido"] || empty($this->requested["email"]["valore"])) && $attemp<$attemps){
            $this->error("This email '" .$this->requested["email"]["valore"]. "' is not valid");
            $this->requested["email"]["valore"] = $this->ask('The email associated to git repository',
                ($this->requested["email"]["valore-default-valido"]?$this->requested["email"]["valore-default"]:$this->requested["email"]["valore"]));
            $this->requested["email"]["valore-valido"] = Email::isValidValue($this->requested["email"]["valore"]);
            $attemp++;
            if ($attemp== $attemps) return $this->error("Exit for invalid email");
        }
    }

    private function readGit($silent)
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

        if(!$silent && !$this->requested["git"]["valore-valido"] && $this->confirm('Do you want add to git repository?')){
            $this->requested["git"]["valore"] = $this->choice('Github or Bitbucket?', ['github', 'bitbucket']);
            $this->requested["git"]["valore-valido"]=true;
        }

        return $this->requested["git"]["valore-valido"];
    }

    private function readGitaction($silent)
    {
        if($silent && !$this->requested["gitaction"]["valore-valido"] && !$this->requested["gitaction"]["valore-valido-default"]){
            $this->exitWork("The action for git is not correct, choice from 'push', 'pull' or 'force'");
        }
        if($silent && !$this->requested["gitaction"]["valore-valido"] && $this->requested["gitaction"]["valore-valido-default"]){
            $this->requested["gitaction"]["valore-valido"] = $this->requested["gitaction"]["valore-valido-default"];
        }
        if(!$silent && !$this->requested["gitaction"]["valore-valido"]){
            $this->requested["gitaction"]["valore"] = $this->choice('What do you want do?', ['push', 'pull', 'force']);
        }
    }

    private function readOrganization($silent)
    {
        if($silent && !$this->requested["organization"]["valore-valido"] && !$this->requested["organization"]["valore-valido-default"]){
            $this->exitWork("The organization for git can't be void");
        }

        if($silent && !$this->requested["organization"]["valore-valido"] && $this->requested["organization"]["valore-valido-default"]){
            $this->requested["organization"]["valore-valido"] = $this->requested["organization"]["valore-valido-default"];
        }

        if(!$this->requested["organization"]["valore-valido"]){
            $this->requested["organization"]["valore"] = $this->ask('Git repository\'s organization');
        }
    }
    
    private function readPassword($silent)
    {

        if($silent && !$this->requested["password"]["valore-valido"] && !$this->requested["password"]["valore-valido-default"]){
            $this->exitWork("The password for git can't be void");
        }

        if($silent && !$this->requested["password"]["valore-valido"] && $this->requested["password"]["valore-valido-default"]){
            $this->requested["password"]["valore-valido"] = $this->requested["password"]["valore-valido-default"];
        }

        if(!$silent && !$this->requested["password"]["valore-valido"]){
            $this->requested["password"]["valore"] = $this->secret('Git repository\'s password');
        }
    }

    private function readType($silent)
    {
        if($silent && !$this->requested["type"]["valore-valido"] && !$this->requested["type"]["valore-valido-default"]){
            $this->exitWork("Type is not correct, choice from 'laravel' or 'normal'");
        }
        if($silent && !$this->requested["type"]["valore-valido"] && $this->requested["type"]["valore-valido-default"]){
            $this->requested["type"]["valore-valido"] = $this->requested["type"]["valore-valido-default"];
        }
        if(!$silent && !$this->requested["type"]["valore-valido"]){
            $this->requested["type"]["valore"] = $this->choice('Project type?', ['laravel', 'normal']);
        }
    }

    private function readUser($silent)
    {

        if($silent && !$this->requested["user"]["valore-valido"] && !$this->requested["user"]["valore-valido-default"]){
            $this->exitWork("The user for git can't be void");
        }

        if($silent && !$this->requested["user"]["valore-valido"] && $this->requested["user"]["valore-valido-default"]){
            $this->requested["user"]["valore-valido"] = $this->requested["user"]["valore-valido-default"];
        }
        if(!$this->requested["user"]["valore-valido"]){
            $this->requested["user"]["valore"] = $this->ask('Git repository\'s username');
        }
    }

    private function exitWork($error)
    {
        $this->error($error);
        exit();
    }

    private function deleteDomain()
    {

    }
}

