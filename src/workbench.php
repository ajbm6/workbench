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
                            {--filehosts : add or remove in local file /etc/hosts}
                            {--packagename= : name of package}
                            {--packagedescr= : description of package}
                            {--packagekeywords= : keywords of package}
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

        $this->createDomainFolder();
        $this->manageRepoGit();
        $this->createVirtualhost($option["filehosts"]);
        if($this->option['filehosts']) {
            $this->addToFileHosts();
        }


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
        $this->requested["packagename"] = $this->prepare($option["packagename"],"packagename");
        $this->requested["packagedescr"] = $this->prepare($option["packagedescr"],"packagedescr");
        $this->requested["packagekeywords"] = $this->prepare($option["packagekeywords"],"packagekeywords");

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
        $filehost=$option["filehosts"];

        $this->validate($argument, $option);
        $action = new Parameters\Action($this);
        $action->read($silent);
        if($this->requested["action"]["valore"]=="delete") {
            $this->error("Attention the virtual host file of ".$this->requested["domein"]["valore"]." will be deleted.");
            $this->confirm("Delete the virtual host file of ".$this->requested["domein"]["valore"]."?");
            $this->deleteVirtualHost($option["filehosts"]); //ToDo

            exit();
        }


        $git = new Parameters\Git($this);
        if($git->read($silent)){
            $gitaction = new Parameters\Gitaction($this);
            $gitaction->read($silent);
            $user = new Parameters\User($this);
            $user->read($silent);
            $password = new Parameters\Password($this);
            $password->read($silent);
            $email = new Parameters\Email($this);
            $email->read($silent);
            $organization = new Parameters\Organization($this);
            $organization->read($silent);
        }

        $domain = new Parameters\Domain($this);
        $domain->read($silent);
        $type = new Parameters\Type($this);
        $type->read($silent);
        $dir = new Parameters\Dir($this);
        $dir->read($silent);

        $sshhost = new Parameters\Sshhost($this);
        if($sshhost->read($silent)) {
            $sshuser = new Parameters\Sshuser($this);
            $sshpassword = new Parameters\Sshpassword($this);
            $sshuser->read($silent);
            $sshpassword->read($silent);
        }
        $packagename = new Parameters\Packagename($this);
        $packagename->read($silent);
        $packagedescr = new Parameters\Packagedescr($this);
        $packagedescr->read($silent);
        $packagekeywords = new Parameters\Packagekeywords($this);
        $packagekeywords->read($silent);

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
                $this->substitute();
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
            $this->error("Domain directory exist.");
            exit();
        }
        $result = File::makeDirectory($this->requested["dir"]['valore'].$this->requested["domain"]['valore']);
    }

    private function checkRepoGithubExist()
    {
        $httphelper = new HttpHelper(new HTTPClient(new Client(),new RequestHelper()));
        $response = $httphelper->sendGet("https://api.github.com/repos/". $this->requested["organization"]['valore'] ."/".$this->requested["domain"]['valore']);
        return ($response->status_code==200 ? true : false);
    }    
    
    private function checkRepoBitbucketExist()
    {
        $httphelper = new HttpHelper(new HTTPClient(new Client(),new RequestHelper()));
        //$response = $httphelper->sendGet("https://api.bitbucket.org/2.0/repositories/". $this->requested["organization"]['valore'] ."/".$this->requested["domain"]['valore']);
        $response = $httphelper->sendGetWithAuth("https://api.bitbucket.org/2.0/repositories/alevento/provgtgta2.net",[],[],"alevento","neicapelli");
        return ($response->status_code==200 ? true : false);
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
        if($this->requested["git"]["valore"]==Parameters\Git::GITHUB) {
            if ($this->requested["gitaction"]["valore"]==Parameters\GitAction::PUSH) {
                if($this->checkRepoGithubExist()) {
                    $this->error("Repo already exist!");
                    exit();
                }
                $this->createRepoGithub();
            }
            if ($this->requested["gitaction"]["valore"]==Parameters\GitAction::PULL) {
                if(!$this->checkRepoGithubExist()) {
                    $this->error("Repo not exist!");
                    exit();
                }

            }

        }

        if($this->requested["git"]["valore"]==Parameters\Git::BITBUCKET) {

            if ($this->requested["gitaction"]["valore"]==Parameters\GitAction::PUSH) {
                if($this->checkRepoBitbucketExist()) {
                    $this->error("Repo already exist!");
                    exit();
                }
                $this->createRepoBitBucket();
            }
            if ($this->requested["gitaction"]["valore"]==Parameters\GitAction::PULL) {
                if(!$this->checkRepoBitbucketExist()) {
                    $this->error("Repo not exist!");
                    exit();
                }
            }
        }

        $this->createAndDownloadFromGit();
    }

    private function createVirtualhost($filehosts)
    {
        $apachedir="/var/www/html/";

        $ssh = new SSH2('192.168.0.29');
        if (!$ssh->login($this->requested['sshuser']['valore'], $this->requested['sshpassword']['valore'])) {
            exit('Login Failed');
        }
        //$ssh->read('/presente/',SSH2::READ_REGEX);
        if($ssh->exec("if [ -e /etc/apache2/sites-available/" . $this->requested['domain']['valore'].".conf ]; then echo 'presente'; else echo 'assente'; fi;")=="presente\n") {
            $this->error('File /etc/apache2/sites-available/'.$this->requested['domain']['valore'].'.conf exist');
            exit('File /etc/apache2/sites-available/'.$this->requested['domain']['valore'].'.conf exist');
        }

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

        $ssh->exec('echo "'.$virtualhost.'" > /etc/apache2/sites-available/'.$this->requested['domain']['valore'].'.conf');
        $ssh->exec('a2ensite '.$this->requested['domain']['valore']);
        $ssh->exec('/etc/init.d/apache2 reload');

        if($filehosts) {
            addToFileHosts($ssh);
        }
    }

    private function addToFileHosts(SSH2 $ssh)
    {

        $output=$ssh->exec("grep -l '127.0.0.1[[:space:]]*provaasd.net' /etc/hosts");
        if($output=="") {
            $ssh->exec('127.0.0.1	'.$this->requested['domain']['valore'].'>/etc/hosts');
        }
    }

    private function removeToFileHosts(SSH2 $ssh)
    {
        $ssh->exec('sed -i "/'.$this->requested['domain']['valore'].'/d" /etc/hosts');
    }



    private function deleteVirtualHost($filehosts)
    {
        $this->error('sadfa');
        $apachedir="/var/www/html/";

        $ssh = new SSH2('192.168.0.29');
        if (!$ssh->login($this->requested['sshuser']['valore'], $this->requested['password']['valore'])) {
            exit('Login Failed');
        }

        $ssh->exec('a2dissite '.$this->requested['domain']['valore']);
        $ssh->exec('/etc/init.d/apache2 reload');
        $ssh->exec("rm /etc/apache2/sites-available/'.$this->requested['domain']['valore'].'.conf");

        if($filehosts) {
            $this->removeToFileHosts($ssh);
        }
    }

    private function substitute()
    {
        $author = Config::get('workbench.substitute.author','Padosoft');
        $emailauthor = Config::get('workbench.substitute.emailauthor','helpdesk@padosoft.com');
        $siteauthor = Config::get('workbench.substitute.siteauthor','www.padosoft.com');
        $vendor = str_replace(" ","_", strtolower(Config::get('workbench.substitute.vendor','Padosoft')));
        $packagename = $this->requested['packagename']['valore'];
        $packagedescr = $this->requested['packagedescr']['valore'];
        $packagekeywords = $this->requested['packagekeywords']['valore'];

        $files = explode(",",Config::get('workbench.substitute.files'));


        foreach($files as $file){

            $fileandpath=$file;
            if(substr($file,0,1)!="/") {
                $fileandpath=$this->requested['dir']['valore'].$this->requested['domain']['valore']."/".$file;
            }
            try {


                $str=file_get_contents($fileandpath);
                $str=str_replace("@@@author", $author,$str);
                $str=str_replace("@@@emailauthor", $emailauthor,$str);
                $str=str_replace("@@@siteauthor", $siteauthor,$str);
                $str=str_replace("@@@vendor", $vendor,$str);
                $str=str_replace("@@@package_name", $packagename,$str);
                $str=str_replace("@@@package_description", $packagedescr,$str);
                $str=str_replace("@@@keywords", $packagekeywords,$str);
                $str=str_replace("@@@date", date("d.m.y"),$str);
                $str=str_replace("@@@year", date("y"),$str);
                $str=str_replace("@@@namespacevendor", ucfirst($vendor),$str);
                $str=str_replace("@@@namespacepackage_name", ucfirst(str_replace("_","", $packagename)),$str);
                $str=str_replace("@@@providerpackage_name", ucfirst(str_replace("_","", $packagename)),$str);


                file_put_contents($fileandpath, $str);
            } catch (\Exception $ex)  {

            }
        }

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

