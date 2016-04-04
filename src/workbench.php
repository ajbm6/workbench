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
        $tuttoOk = true;

        $silent=$option["silent"];
        $this->validate($argument, $option);

        
        $name = $this->ask('What is your name?');
        $password = $this->secret('What is the password?');
        if ($this->confirm('Do you wish to continue? [y|N]')) {
            //
        }
        $name = $this->anticipate('What is your name?', ['Taylor', 'Dayle']);
        $name = $this->choice('What is your name?', ['Taylor', 'Dayle'], false);

        $this->notifyResult( $tuttoOk);

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
        if(class_exists($class))
        {
            $validClass=true;
            $myclass=new $class();
            if(!$myclass::isValidValue($val))
            {
                $validVal=false;
            }
        }

        $validDefault=true;
        $valDefault=Config::get('workbench.action');

        if(!isset($valDefault) || $valDefault="")
        {
            $validDefault=false;
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

        $requested["action"] = $this->prepare($argument["action"],"Padosoft\\Workbench\\Action");
        $requested["domain"] = $this->prepare($argument["domain"],"Padosoft\\Workbench\\Domain");
        $requested["type"] = $this->prepare($option["type"],"Padosoft\\Workbench\\Type");
        $requested["dir"] = $this->prepare($option["dir"],"Padosoft\\Workbench\\Dir");
        $requested["git"] = $this->prepare($option["git"],"Padosoft\\Workbench\\Dir");
        $requested["gitaction"] = $this->prepare($option["gitaction"],"Padosoft\\Workbench\\Gitaction");
        $requested["user"] = $this->prepare($option["user"],"Padosoft\\Workbench\\User");
        $requested["password"] = $this->prepare($option["password"],"Padosoft\\Workbench\\Password");
        $requested["email"] = $this->prepare($option["email"],"Padosoft\\Workbench\\Email");
        $requested["organization"] = $this->prepare($option["organization"],"Padosoft\\Workbench\\Organization");
    }

}

