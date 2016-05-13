<?php

namespace Padosoft\Workbench;


use Illuminate\Console\Command;
use Config;
use GrahamCampbell\GitHub\Facades\GitHub;
use Padosoft\Workbench\Parameters;
use File;
use GuzzleHttp\Client;
use Padosoft\HTTPClient\HTTPClient;
use Padosoft\HTTPClient\RequestHelper;
use Padosoft\HTTPClient\Response;
use Padosoft\HTTPClient\HttpHelper;
use GitWrapper\GitWrapper;
use phpseclib\Net\SSH2;

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

    private $parameters = array();

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

        $this->readParameters($option, $argument);
        //$this->createDomainFolder();
        $this->createVirtualhost();
        //$this->manageRepoGit();

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

        /*foreach ($argument as $key => $value) {
            $requested[$key] = $this->prepare($value,"Padosoft\\Workbench\\".ucfirst($key));
        }
        foreach ($option as $key => $value) {
            $requested[$key] = $this->prepare($value,"Padosoft\\Workbench\\".ucfirst($key));
        }*/
    }


    private function readParameters($option,$argument)
    {
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
            $domain->deleteDomain(); //ToDo
            exit();
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

        if($sshhost->read($silent)) {
            $sshuser = new Parameters\Sshuser($this);
            $sshpassword = new Parameters\Sshpassword($this);
            $sshuser->read($silent);
            $sshpassword->read($silent);
        }
        return true;
    }

    private function createAndDownloadFromGit()
    {
        try {
            $gitWrapper = new GitWrapper();
            $gitWorkingCopy=$gitWrapper->init($this->requested['dir']['valore'].$this->requested['domain']['valore'],[]);
            $gitWrapper->git("config --global user.name ".$this->requested['user']['valore']);
            $gitWrapper->git("config --global user.email ".$this->requested['email']['valore']);
            $gitWrapper->git("config --global user.password ".$this->requested['password']['valore']);
            $gitWorkingCopy->add('.');
            switch($this->requested['type']['valore']) {
                case Parameters\Type::AGNOSTIC_PACKAGE:
                    $gitWorkingCopy->addRemote('origin',"https://".$this->requested['user']['valore'].":".$this->requested['password']['valore']."@github.com/padosoft/".Config::get('workbench.type_repository.agnostic_package').".git" );
                    break;
                case Parameters\Type::LARAVEL_PACKAGE:
                    $gitWorkingCopy->addRemote('origin',"https://".$this->requested['user']['valore'].":".$this->requested['password']['valore']."@github.com/padosoft/".Config::get('workbench.type_repository.laravel_package').".git" );
                    break;
                case Parameters\Type::LARAVEL:
                    $gitWorkingCopy->addRemote('origin',"https://".$this->requested['user']['valore'].":".$this->requested['password']['valore']."@github.com/padosoft/".Config::get('workbench.type_repository.laravel').".git" );
                    break;
                case Parameters\Type::NORMAL:
                    $gitWorkingCopy->addRemote('origin',"https://".$this->requested['user']['valore'].":".$this->requested['password']['valore']."@github.com/padosoft/".Config::get('workbench.type_repository.normal').".git" );
                    break;
            }
            if($this->requested['gitaction']['valore']==Parameters\GitAction::PULL) {
                $gitWorkingCopy->removeRemote('origin');
                $gitWorkingCopy->addRemote('origin',"https://".$this->requested['user']['valore'].":".$this->requested['password']['valore']."@".$this->requested["git"]["valore"].".com/".$this->requested['organization']['valore']."/".$this->requested['domain']['valore'].".git");
            }
            $gitWorkingCopy->pull('origin','master');
            if($this->requested['gitaction']['valore']==Parameters\GitAction::PUSH) {
                $gitWorkingCopy->removeRemote('origin');
                $gitWorkingCopy->addRemote('origin',"https://".$this->requested['user']['valore'].":".$this->requested['password']['valore']."@".$this->requested["git"]["valore"].".com/".$this->requested['organization']['valore']."/".$this->requested['domain']['valore'].".git");
                $gitWorkingCopy->add('.');
                $gitWorkingCopy->push('origin','master');
            }
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
            exit();
        }
        return true;
    }

    private function createDomainFolder()
    {
        if(File::exists($this->requested["dir"]['valore'].$this->requested["domain"]['valore'])){
            $this->command->error("Domain directory exist.");
            exit();
        }
        $result = File::makeDirectory($this->requested["dir"]['valore'].$this->requested["domain"]['valore']);
    }

    private function createRepoGithub()
    {
        $response = new \Padosoft\HTTPClient\Response();
        try {
            $httphelper = new HttpHelper(new HTTPClient(new Client(),new RequestHelper()));
            $response = $httphelper->sendPostJsonWithAuth("https://api.github.com/orgs/".$this->requested['organization']['valore']."/repos",['name'=>$this->requested["domain"]["valore"]],$this->requested["user"]["valore"],$this->requested["password"]["valore"]);
            if($response->status_code==422) {
                $this->error("Repository esistente");
                exit();
            }
            if($response->status_code==401) {
                $this->error("Bad credentials");
                exit();
            }
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
            exit();
        }

    }

    private function createRepoBitBucket()
    {
        $response = new \Padosoft\HTTPClient\Response();
        try {
            $httphelper = new HttpHelper(new HTTPClient(new Client(),new RequestHelper()));
            $response = $httphelper->sendPostJsonWithAuth("https://api.bitbucket.org/2.0/repositories/".$this->requested['organization']['valore']."/".$this->requested['domain']['valore'],['scm'=>'git', 'is_private'=>'true', 'name'=>$this->requested["domain"]["valore"]],$this->requested["user"]["valore"],$this->requested["password"]["valore"]);
            if($response->status_code==400) {
                $this->error($response->body);
                exit();
            }
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
            exit();
        }

    }

    private function manageRepoGit()
    {
        if($this->requested["git"]["valore-valido"] && $this->requested["git"]["valore"]==Parameters\Git::GITHUB) {
            $this->createRepoGithub();
            $this->createAndDownloadFromGit();
        }
        if($this->requested["git"]["valore-valido"] && $this->requested["git"]["valore"]==Parameters\Git::BITBUCKET) {
            $this->createRepoBitBucket();
            $this->createAndDownloadFromGit();
        }
    }

    private function createVirtualhost()
    {
        $apachedir="/var/www/html/";

        $virtualhost = "
		<VirtualHost *:80>
			ServerAdmin ".$this->requested['email']['valore']."
			ServerName ".$this->requested['domain']['valore']."
			ServerAlias ".$this->requested['domain']['valore']."
			DocumentRoot ".$apachedir.$this->requested['domain']['valore']."
			<Directory />
				AllowOverride All
			</Directory>
			<Directory ".$apachedir.$this->requested['domain']['valore'].">
				Options Indexes FollowSymLinks MultiViews
				AllowOverride all
				Require all granted
			</Directory>
			ErrorLog ".$apachedir.$this->requested['domain']['valore']."/apache2_logs/apache2-error.log
			LogLevel error
			CustomLog ".$apachedir.$this->requested['domain']['valore']."/apache2_logs/apache2-access.log combined
		</VirtualHost>";
        $ssh = new SSH2('192.168.0.29');
        if (!$ssh->login('root', 'padosoft2015')) {
            exit('Login Failed');
        }
        $ssh->exec('echo "'.$virtualhost.'" > /etc/apache2/sites-available/'.$this->requested['domain']['valore'].'.conf');
        $ssh->exec('a2ensite '.$this->requested['domain']['valore']);


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

