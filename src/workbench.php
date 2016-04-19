<?php

namespace Padosoft\Workbench;


use Illuminate\Console\Command;
use Config;
use GrahamCampbell\GitHub\Facades\GitHub;
use Padosoft\Workbench\Parameters;
use File;
use GuzzleHttp\Client;


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
                            {--sshhost= : host ssh}
                            {--sshuser= : user ssh}
                            {--sshpassword= : password ssh}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = <<<EOF
The <info>workbench:new</info> ....
EOF;

    private $requested = array();
    
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
        $action = new Parameters\Action($this);
        $domain = new Parameters\Domain($this);
        $type = new Parameters\Type($this);
        $dir = new Parameters\Dir($this);
        $git = new Parameters\Git($this);
        $sshhost = new Parameters\Sshhost($this);

        
        $action->read($silent);
        $domain->read($silent);
        if($this->requested["action"]["valore"]=="delete"){
            $domain->deleteDomain();
            return;
        }
        $type->read($silent);
        $dir->read($silent);
        if($git->read($silent)){
            $gitaction = new Parameters\Gitaction($this);
            $user = new Parameters\User($this);
            $password = new Parameters\Password($this);
            $email = new Parameters\Email($this);
            $organization = new Parameters\Organization($this);
            $gitaction->read($silent);
            $user->read($silent);
            $password->read($silent);
            $email->read($silent);
            $organization->read($silent);
        }

        if($sshhost->read($silent)){
            $sshuser = new Parameters\Sshuser($this);
            $sshpassword = new Parameters\Sshpassword($this);
            $sshuser->read($silent);
            $sshpassword->read($silent);
        }

        $this->createDomainFolder();




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
        $classwithnamespace = "Padosoft\\Workbench\\Parameters\\".ucfirst($class);
        $myclass=null;
        if(class_exists($classwithnamespace))
        {
            $validClass=true;
            $myclass=new $classwithnamespace($this);
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
        $this->requested["dir"] = $this->prepare(Parameters\Dir::adjustPath($option["dir"]),"dir");
        $this->requested["git"] = $this->prepare($option["git"],"git");
        $this->requested["gitaction"] = $this->prepare($option["gitaction"],"gitaction");
        $this->requested["user"] = $this->prepare($option["user"],"user");
        $this->requested["password"] = $this->prepare($option["password"],"password");
        $this->requested["email"] = $this->prepare($option["email"],"email");
        $this->requested["organization"] = $this->prepare($option["organization"],"organization");
        $this->requested["sshhost"] = $this->prepare($option["sshhost"],"sshhost");
        $this->requested["sshuser"] = $this->prepare($option["sshuser"],"sshuser");
        $this->requested["sshpassword"] = $this->prepare($option["sshpassword"],"sshpassword");

    }

    public function createDomainFolder() {
        if(File::exists($this->requested["dir"]['valore'].$this->requested["domain"]['valore'])){
            $this->command->error("Domain directory exist.");
            exit();
        }
        $result = File::makeDirectory($this->requested["dir"]['valore'].$this->requested["domain"]['valore']);
    }



    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }


}

