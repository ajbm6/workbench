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
use League\CommonMark\CommonMarkConverter;

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
                            {--d|dirtype= : project dir type, public or private}
                            {--g|git= : github or bitbucket}
                            {--a|gitaction= : push or pull}
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
        if(!substr($this->requested["type"]['valore'],-7) == 'package') {
            $this->createVirtualhost($option["silent"],$option["filehosts"]);
            if($this->option['filehosts']) {
                $this->addToFileHosts();
            }
            $this->info("Creation complete");
        }
        //$this->apigeneration();
        if(substr($this->requested["type"]['valore'],-7) == 'package') {
            $this->apigeneration();
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
        //$validDefault=false;
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
        $this->requested["dirtype"] = $this->prepare($option["dirtype"],"dirtype");
        $this->requested["dir"] = $this->prepare('',"dir");
        $this->requested["git"] = $this->prepare($option["git"],"git");
        $this->requested["gitaction"] = $this->prepare(Parameters\GitAction::PUSH,"gitaction");
        $this->requested["user"] = $this->prepare($option["user"],"user");
        $this->requested["password"] = $this->prepare($option["password"],"password");
        $this->requested["email"] = $this->prepare($option["email"],"email");
        $this->requested["organization"] = $this->prepare($option["organization"],"organization");
        $this->requested["sshhost"] = $this->prepare($option["sshhost"],"sshhost");
        $this->requested["sshuser"] = $this->prepare($option["sshuser"],"sshuser");
        $this->requested["sshpassword"] = $this->prepare($option["sshpassword"],"sshpassword");
        $this->requested["packagename"] = $this->prepare($this->requested["domain"]["valore"],"packagename");
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

        $domain = new Parameters\Domain($this);
        $domain->read($silent);
        $action = new Parameters\Action($this);
        $action->read($silent);
        $type = new Parameters\Type($this);
        $type->read($silent);



        if($this->requested["action"]["valore"]=="delete" && substr($this->requested["type"]['valore'],-7) == 'package') {
            $this->info("No action for delete a package");
            exit();
        }

        if(substr($this->requested["type"]['valore'],-7) != 'package') {
            $sshhost = new Parameters\Sshhost($this);
            if($sshhost->read($silent)) {
                $sshuser = new Parameters\Sshuser($this);
                $sshuser->read($silent);
                $sshpassword = new Parameters\Sshpassword($this);
                $sshpassword->read($silent);
            }
        }

        if($this->requested["action"]["valore"]=="delete") {
            $this->error("Attention the virtual host file of ".$this->requested["domain"]["valore"]." will be deleted.");
            if(!$silent) {
                $this->confirm("Delete the virtual host file of ".$this->requested["domain"]["valore"]."?");
            }
            $this->deleteVirtualHost($option["filehosts"]); //ToDo
            $this->info("Deleted complete");
            exit();
        }

        $organization = new Parameters\Organization($this);
        $organization->read($silent);



        $dirtype = new Parameters\Dirtype($this);
        $dirtype->read($silent);

        $git = new Parameters\Git($this);
        if($git->read($silent)){
            //$gitaction = new Parameters\Gitaction($this);
            //$gitaction->read($silent);
            $user = new Parameters\User($this);
            $user->read($silent);
            $password = new Parameters\Password($this);
            $password->read($silent);
            $email = new Parameters\Email($this);
            $email->read($silent);
            //$organization = new Parameters\Organization($this);
            //$organization->read($silent);
        }


        $packagename = new Parameters\Packagename($this);
        $packagename->read($silent);
        $packagedescr = new Parameters\Packagedescr($this);
        $packagedescr->read($silent);
        $packagekeywords = new Parameters\Packagekeywords($this);
        $packagekeywords->read($silent);

        $this->info("Parameters read");
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
                    $this->info("Donwloading skeleton agnostic package...");
                    break;
                case Parameters\Type::LARAVEL_PACKAGE:
                    $gitWorkingCopy->addRemote('origin',"https://".$this->requested['user']['valore'].":".$this->requested['password']['valore']."@github.com/padosoft/".Config::get('workbench.type_repository.laravel_package').".git" );
                    $this->info("Donwloading skeleton laravel package...");
                    break;
                case Parameters\Type::LARAVEL:
                    $gitWorkingCopy->addRemote('origin',"https://".$this->requested['user']['valore'].":".$this->requested['password']['valore']."@github.com/padosoft/".Config::get('workbench.type_repository.laravel').".git" );
                    $this->info("Donwloading skeleton laravel project...");
                    break;
                case Parameters\Type::NORMAL:
                    $gitWorkingCopy->addRemote('origin',"https://".$this->requested['user']['valore'].":".$this->requested['password']['valore']."@github.com/padosoft/".Config::get('workbench.type_repository.normal').".git" );
                    $this->info("Donwloading skeleton normal project...");
                    break;
            }
            if($this->requested['gitaction']['valore']==Parameters\GitAction::PULL) {
                $this->info("Donwloading repo...");
                $gitWorkingCopy->removeRemote('origin');
                $gitWorkingCopy->addRemote('origin',"https://".$this->requested['user']['valore'].":".$this->requested['password']['valore']."@".$this->requested["git"]["valore"].".com/".$this->requested['organization']['valore']."/".$this->requested['domain']['valore'].".git");
            }
            $gitWorkingCopy->pull('origin','master');
            $this->info("Donwload complete.");
            if($this->requested['gitaction']['valore']==Parameters\GitAction::PUSH) {
                $gitWorkingCopy->removeRemote('origin');
                $gitWorkingCopy->addRemote('origin',"https://".$this->requested['user']['valore'].":".$this->requested['password']['valore']."@".$this->requested["git"]["valore"].".com/".$this->requested['organization']['valore']."/".$this->requested['domain']['valore'].".git");
                $this->substitute();
                $gitWorkingCopy->add('.');
                $gitWorkingCopy->commit("Substitute");
                $this->info("Uploading repo...");
                $gitWorkingCopy->push('origin','master');
                $this->info("Upload complete");
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

        $result = File::makeDirectory($this->requested["dir"]['valore'].$this->requested["domain"]['valore'],493,true);
        if(substr($this->requested["type"]['valore'],-7) != 'package') {
            File::makeDirectory(Parameters\Dir::adjustPath($this->requested["dir"]['valore'].$this->requested["domain"]['valore']).'www/',493,true);
            File::makeDirectory(Parameters\Dir::adjustPath($this->requested["dir"]['valore'].$this->requested["domain"]['valore']).'apache2_logs/',493,true);
        }

        if(substr($this->requested["type"]['valore'],-7) == 'package') {
            File::makeDirectory(Parameters\Dir::adjustPath(Config::get('workbench.dirtype.'.$this->requested["dirtype"]['valore'].'.doc').$this->requested["organization"]['valore']).$this->requested["domain"]['valore'],493,true);
            File::makeDirectory(Parameters\Dir::adjustPath(Config::get('workbench.dirtype.'.$this->requested["dirtype"]['valore'].'.doc').$this->requested["organization"]['valore']).$this->requested["domain"]['valore'].'/dev-master',493,true);
            File::makeDirectory(Parameters\Dir::adjustPath(Config::get('workbench.dirtype.'.$this->requested["dirtype"]['valore'].'.doc').$this->requested["organization"]['valore']).$this->requested["domain"]['valore'].'/resources',493,true);
        }


        $this->info("Domain dir created at ".$this->requested["dir"]['valore'].$this->requested["domain"]['valore']);
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
            $this->info("Created repo https://github.com/".$this->requested['organization']["valore"]."/".$this->requested["domain"]["valore"]);
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
            $this->info("Created repo https://bitbucket.org/".$this->requested['organization']."/".$this->requested["domain"]["valore"]);
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

    private function createVirtualhost(bool $silent,bool $filehosts)
    {
        $this->info("Creating virtualhost");
        $apachedir=Parameters\Dir::adjustPath(Config::get('workbench.dir.'.$this->requested['dirtype']['valore'].'.apache2'));
        $rootdir=Parameters\Dir::adjustPath($apachedir.$this->requested['domain']['valore']);
        $webdir=$rootdir.'www/';
        if($this->requested['type']['valore']=='laravel') {
            $webdir=$webdir.'public/';
        }
        $ssh = new SSH2($this->requested['sshhost']['valore']);
        if (!$ssh->login($this->requested['sshuser']['valore'], $this->requested['sshpassword']['valore'])) {
            if($silent) {
                exit('SSH login failed at '.$this->requested['sshuser']['valore'].'@'.$this->requested['sshhost']['valore']);
            }
            exit('SSH login failed at '.$this->requested['sshuser']['valore'].'@'.$this->requested['sshhost']['valore']);
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
			DocumentRoot ".$webdir."
			<Directory ".$webdir.">
				Options Indexes FollowSymLinks MultiViews
				AllowOverride all
				Require all granted
			</Directory>
			ErrorLog ".$rootdir."apache2_logs/apache2-error.log
			LogLevel error
			CustomLog ".$rootdir."apache2_logs/apache2-access.log combined
		</VirtualHost>";

        $ssh->exec('echo "'.$virtualhost.'" > /etc/apache2/sites-available/'.$this->requested['domain']['valore'].'.conf');
        $ssh->exec('a2ensite '.$this->requested['domain']['valore']);
        $ssh->exec('/etc/init.d/apache2 reload');
        $this->info("Virtualhost created");
        if($filehosts) {
            addToFileHosts($ssh);
        }
    }

    private function addToFileHosts(SSH2 $ssh)
    {
        $this->info("Adding hosts in localhost");
        $output=$ssh->exec("grep -l '127.0.0.1[[:space:]]*provaasd.net' /etc/hosts");
        if($output=="") {
            $ssh->exec('127.0.0.1	'.$this->requested['domain']['valore'].'>/etc/hosts');
        }
        $this->info("Host added");
    }

    private function removeToFileHosts(SSH2 $ssh)
    {
        $this->info("Remove from host");
        $ssh->exec('sed -i "/'.$this->requested['domain']['valore'].'/d" /etc/hosts');
        $this->info("Host removed");
    }



    private function deleteVirtualHost($filehosts)
    {
        $this->info("Deleting virtualhost");
        $this->error('Attenzione il virtual host di '.$this->requested['domain']['valore']);
        $apachedir="/var/www/html/";

        $ssh = new SSH2($this->requested['sshhost']['valore']); //ToDo
        if (!$ssh->login($this->requested['sshuser']['valore'], $this->requested['sshpassword']['valore'])) {
            exit('SSH login failed at '.$this->requested['sshuser']['valore'].'@'.$this->requested['sshuser']['valore']);
        }

        $ssh->exec('a2dissite '.$this->requested['domain']['valore']);
        $ssh->exec('/etc/init.d/apache2 reload');
        $ssh->exec('rm /etc/apache2/sites-available/'.$this->requested['domain']['valore'].'.conf');
        $this->info("Virtualhost deleted");
        if($filehosts) {
            $this->removeToFileHosts($ssh);
        }

    }

    private function substitute()
    {
        $this->info("Changing value in files");
        $author = Config::get('workbench.substitute.author','Padosoft');
        $emailauthor = Config::get('workbench.substitute.emailauthor','helpdesk@padosoft.com');
        $siteauthor = Config::get('workbench.substitute.siteauthor','www.padosoft.com');
        $vendor = str_replace(" ","_", strtolower(Config::get('workbench.substitute.vendor','Padosoft')));
        $packagename = $this->requested['packagename']['valore'];
        $packagedescr = $this->requested['packagedescr']['valore'];
        $packagekeywords = $this->requested['packagekeywords']['valore'];
        $organization = $this->requested['organization']['valore'];
        
        $files = explode(",",Config::get('workbench.substitute.files'));

        foreach($files as $file) {

            $fileandpath=$file;
            if(substr($file,0,1)!="/") {
                $fileandpath=$this->requested['dir']['valore'].$this->requested['domain']['valore']."/".$file;
            }
            try {
                $this->info("Changing in ".$fileandpath);

                $str=file_get_contents($fileandpath);
                $str=str_replace("@@@author", $author,$str);
                $str=str_replace("@@@emailauthor", $emailauthor,$str);
                $str=str_replace("@@@siteauthor", $siteauthor,$str);
                $str=str_replace("@@@vendor", $vendor,$str);
                $str=str_replace("@@@organization", $organization,$str);
                $str=str_replace("@@@package_name", $packagename,$str);
                $str=str_replace("@@@package_description", $packagedescr,$str);
                $str=str_replace("@@@keywords", $packagekeywords,$str);
                $str=str_replace("@@@date", date("d.m.y"),$str);
                $str=str_replace("@@@year", date("y"),$str);
                $str=str_replace("@@@namespacevendor", ucfirst($vendor),$str);
                $str=str_replace("@@@namespacepackage_name", ucfirst(str_replace("-","", $packagename)),$str);
                $str=str_replace("@@@providerpackage_name", ucfirst(str_replace("-","", $packagename)),$str);
                


                file_put_contents($fileandpath, $str);
            } catch (\Exception $ex)  {

            }
        }
        $this->info("Changing complete");
    }

    public function apigeneration()
    {
        $source = $this->requested['dir']['valore'].$this->requested['domain']['valore'];
        $destination = \Padosoft\Workbench\Parameters\Dir::adjustPath(Config::get('workbench.dirtype.'.$this->requested['dirtype']['valore'].'.doc').$this->requested['organization']['valore']).$this->requested['domain']['valore'];
        exec('C:/xampp/php/php.exe Y:/Public/common-dev-lib/apigen.phar generate --source '.$source.' --destination '.$destination.'/dev-master');

        File::copyDirectory($destination.'/dev-master/resources/', $destination.'/resources/');
        $readmepathsource = \Padosoft\Workbench\Parameters\Dir::adjustPath($source).'readme.md';
        $readmepathdestination = \Padosoft\Workbench\Parameters\Dir::adjustPath($destination).'index.html';
        $this->transformReadmeMd($readmepathsource, $readmepathdestination);

        $gitWrapper = new GitWrapper();
        $gitWorkingCopy=$gitWrapper->init($destination,[]);
        $gitWrapper->git("config --global user.name ".$this->requested['user']['valore']);
        $gitWrapper->git("config --global user.email ".$this->requested['email']['valore']);
        $gitWrapper->git("config --global user.password ".$this->requested['password']['valore']);
        $gitWorkingCopy->addRemote('origin',"https://".$this->requested['user']['valore'].":".$this->requested['password']['valore']."@github.com/".$this->requested['organization']['valore']."/".$this->requested['domain']['valore'].".git" );
        $gitWorkingCopy->checkoutNewBranch('gh-pages');
        $gitWorkingCopy->add('.');
        $gitWorkingCopy->commit('Workbench commit');
        $gitWorkingCopy->push('origin','gh-pages');

    }

    public function transformReadmeMd($readmepathsource,$readmepathdestination) {

        if(!File::exists($readmepathsource)) {
            $this->error('File '.$readmepathsource.' not exist');
            exit();
        }

        $dir = \Padosoft\Workbench\Parameters\Dir::adjustPath(__DIR__).'resources/index.html';
        if(!File::exists($dir)) {
            $this->error('File '.$dir.' not exist');
            exit();
        }
        File::copy($dir,$readmepathdestination);
        $index = file_get_contents($readmepathdestination);
        $index = str_replace('@@@package_name', $this->requested['packagename']['valore'],$index);
        $readme = file_get_contents($readmepathsource);
        $converter = new CommonMarkConverter();
        $index = str_replace("@@@readme", $converter->convertToHtml($readme),$index);
        $documentation="<h1>API Documentation</h1>
<p>Please see API documentation at http://".$this->requested['organization']['valore'].".github.io/".$this->requested['packagename']['valore']."</p>";
        $documentation_mod = "<a name=api-documentation ></a>"."<h1>API Documentation</h1>
<p>Please see API documentation at <a href ='http://".$this->requested['organization']['valore'].".github.io/".$this->requested['packagename']['valore']."'>".$this->requested['packagename']['valore']."</a></p>";

        $destination = File::dirname($readmepathdestination);
        $list = array_diff(File::directories($destination),array($destination.'\resources'));
        $list = array_diff($list,array($destination.'/resources'));
        $documentation_mod = $documentation_mod."<ul>";
        foreach ($list as $tag) {
            $tag = File::basename(\Padosoft\Workbench\Parameters\Dir::adjustPath($tag));
            $documentation_mod = $documentation_mod."<li><a href = 'https://".$this->requested['organization']['valore'].".github.io/".$this->requested['domain']['valore']."/".$tag."'>".$tag."</a></li>";
        }
        $documentation_mod = $documentation_mod."</ul>";
        $index = str_replace($documentation, $documentation_mod,$index);

        file_put_contents($readmepathdestination, $index);
        
        
        
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

