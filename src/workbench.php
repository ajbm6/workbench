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
use Padosoft\Workbench\WorkbenchSettings;
use Padosoft\Workbench\WorkbenchApiGeneration;


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
                            {--t|type= : laravel, normal, laravel_package or agnostic_package}
                            {--d|dirtype= : project dir type, public or private}
                            {--g|git= : github or bitbucket}
                            {--a|gitaction= : push or pull}
                            {--githookenable : enable pre commit hook}
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


    private $workbenchSettings;

    private $parameters = array();

    function __construct() {
        if(is_null($this->workbenchSettings) ){
            $this->workbenchSettings = new WorkbenchSettings($this);
        }
        parent::__construct();
    }



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
        if($option["githookenable"]) $this->addHooks();

        if(substr($this->workbenchSettings->requested["type"]['valore'],-7) != 'package' && $this->workbenchSettings->requested["sshhost"]["valore-valido"]) {
            $this->createVirtualhost($option["silent"],$option["filehosts"]);
            if($this->option['filehosts']) {
                $this->addToFileHosts();
            }

            $this->info("Creation complete");
        }

        $apiSamiGeneration = new WorkbenchApiGeneration($this->workbenchSettings,$this);
        //$this->apigeneration();
        if(substr($this->workbenchSettings->requested["type"]['valore'],-7) == 'package') {

            $apiSamiGeneration->apiSamiGeneration();
        }
    }



    private function validate($argument, $option)
    {
            $this->workbenchSettings->prepare($argument["action"],"action");
            $this->workbenchSettings->prepare($argument["domain"],"domain");
            $this->workbenchSettings->prepare($option["type"],"type");
            $this->workbenchSettings->prepare($option["dirtype"],"dirtype");
            $this->workbenchSettings->prepare('',"dir");
            $this->workbenchSettings->prepare($option["git"],"git");
            $this->workbenchSettings->prepare(Parameters\GitAction::PUSH,"gitaction");
            $this->workbenchSettings->prepare($option["githookenable"],"githookenable");
            $this->workbenchSettings->prepare($option["user"],"user");
            $this->workbenchSettings->prepare($option["password"],"password");
            $this->workbenchSettings->prepare($option["email"],"email");
            $this->workbenchSettings->prepare($option["organization"],"organization");
            $this->workbenchSettings->prepare($option["sshhost"],"sshhost");
            $this->workbenchSettings->prepare($option["sshuser"],"sshuser");
            $this->workbenchSettings->prepare($option["sshpassword"],"sshpassword");
            $this->workbenchSettings->prepare($option["packagename"],"packagename");
            $this->workbenchSettings->prepare($option["packagedescr"],"packagedescr");
            $this->workbenchSettings->prepare($option["packagekeywords"],"packagekeywords");

        /*foreach ($argument as $key => $value) {
            $requested[$key] = $this->workbenchSettings->prepare($value,"Padosoft\\Workbench\\".ucfirst($key));
        }
        foreach ($option as $key => $value) {
            $requested[$key] = $this->workbenchSettings->prepare($value,"Padosoft\\Workbench\\".ucfirst($key));
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

        if($this->workbenchSettings->requested["action"]["valore"]=="delete" && substr($this->workbenchSettings->requested["type"]['valore'],-7) == 'package') {
            $this->info("No action for delete a package");
            exit();
        }

        if(substr($this->workbenchSettings->requested["type"]['valore'],-7) != 'package') {
            $sshhost = new Parameters\Sshhost($this);
            if($sshhost->read($silent)) {
                $sshuser = new Parameters\Sshuser($this);
                $sshuser->read($silent);
                $sshpassword = new Parameters\Sshpassword($this);
                $sshpassword->read($silent);
            }
        }

        if($this->workbenchSettings->requested["action"]["valore"]=="delete") {
            $this->error("Attention the virtual host file of ".$this->workbenchSettings->requested["domain"]["valore"]." will be deleted.");
            if(!$silent) {
                $this->confirm("Delete the virtual host file of ".$this->workbenchSettings->requested["domain"]["valore"]."?");
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
            $githookenable = new Parameters\Githookenable($this);
            $githookenable->read($silent);

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

    private function createAndDownloadFromGit(bool $upload)
    {
        try {
            $gitWrapper = new GitWrapper();
            if(substr($this->workbenchSettings->requested["type"]['valore'],-7) != 'package') {
                $gitWorkingCopy=$gitWrapper->init(\Padosoft\Workbench\Parameters\Dir::adjustPath($this->workbenchSettings->requested['dir']['valore'].$this->workbenchSettings->requested['domain']['valore']."/www",[]));
            }
            if(substr($this->workbenchSettings->requested["type"]['valore'],-7) == 'package') {
                $gitWorkingCopy=$gitWrapper->init(\Padosoft\Workbench\Parameters\Dir::adjustPath($this->workbenchSettings->requested['dir']['valore'].$this->workbenchSettings->requested['domain']['valore'],[]));
            }
            //$gitWorkingCopy=$gitWrapper->init($this->workbenchSettings->requested['dir']['valore'].$this->workbenchSettings->requested['domain']['valore'],[]);
            $gitWrapper->git("config --global user.name ".$this->workbenchSettings->requested['user']['valore']);
            $gitWrapper->git("config --global user.email ".$this->workbenchSettings->requested['email']['valore']);
            $gitWrapper->git("config --global user.password ".$this->workbenchSettings->requested['password']['valore']);
            $gitWorkingCopy->add('.');

            switch($this->workbenchSettings->requested['type']['valore']) {
                case Parameters\Type::AGNOSTIC_PACKAGE:
                    $gitWorkingCopy->addRemote('origin',"https://".$this->workbenchSettings->requested['user']['valore'].":".$this->workbenchSettings->requested['password']['valore']."@github.com/padosoft/".Config::get('workbench.type_repository.agnostic_package').".git" );
                    $this->info("Downloading skeleton agnostic package...");
                    break;
                case Parameters\Type::LARAVEL_PACKAGE:
                    $gitWorkingCopy->addRemote('origin',"https://".$this->workbenchSettings->requested['user']['valore'].":".$this->workbenchSettings->requested['password']['valore']."@github.com/padosoft/".Config::get('workbench.type_repository.laravel_package').".git" );
                    $this->info("Downloading skeleton laravel package...");
                    break;
                case Parameters\Type::LARAVEL:
                    $gitWorkingCopy->addRemote('origin',"https://".$this->workbenchSettings->requested['user']['valore'].":".$this->workbenchSettings->requested['password']['valore']."@github.com/padosoft/".Config::get('workbench.type_repository.laravel').".git" );
                    $this->info("Downloading skeleton laravel project...");
                    break;
                case Parameters\Type::NORMAL:
                    $gitWorkingCopy->addRemote('origin',"https://".$this->workbenchSettings->requested['user']['valore'].":".$this->workbenchSettings->requested['password']['valore']."@github.com/padosoft/".Config::get('workbench.type_repository.normal').".git" );
                    //$this->info("Downloading skeleton normal project...");
                    break;
            }
            if($this->workbenchSettings->requested['gitaction']['valore']==Parameters\GitAction::PULL) {
                $this->info("Donwloading repo...");
                $gitWorkingCopy->removeRemote('origin');
                $gitWorkingCopy->addRemote('origin',"https://".$this->workbenchSettings->requested['user']['valore'].":".$this->workbenchSettings->requested['password']['valore']."@".$this->workbenchSettings->requested["git"]["valore"].".com/".$this->workbenchSettings->requested['organization']['valore']."/".$this->workbenchSettings->requested['packagename']['valore'].".git");
            }
            if($this->workbenchSettings->requested['type']['valore']!=Parameters\Type::NORMAL) {
                $gitWorkingCopy->pull('origin','master');
                $this->info("Download complete.");
            }

            if($upload && $this->workbenchSettings->requested['gitaction']['valore']==Parameters\GitAction::PUSH) {
                $gitWorkingCopy->removeRemote('origin');
                $extension = ($this->workbenchSettings->requested["git"]["valore"]==Parameters\Git::BITBUCKET ? "org" : "com");
                $gitWorkingCopy->addRemote('origin',"https://".$this->workbenchSettings->requested['user']['valore'].":".$this->workbenchSettings->requested['password']['valore']."@".$this->workbenchSettings->requested["git"]["valore"].".". $extension ."/".$this->workbenchSettings->requested['organization']['valore']."/".$this->workbenchSettings->requested['packagename']['valore'].".git");
                $this->substitute();
                $gitWorkingCopy->add('.');
                $gitWorkingCopy->commit("Substitute");
                $this->info("Uploading repo...");
                $gitWorkingCopy->push('origin','master');
                $this->info("Upload complete");
            }


            $dir = \Padosoft\Workbench\Parameters\Dir::adjustPath(__DIR__).'config/pre-commit';
            if(!File::exists($dir)) {
                $this->error('File '.$dir.' not exist');
                exit();
            }

            if(substr($this->workbenchSettings->requested["type"]['valore'],-7) != 'package') {
                File::copy($dir,\Padosoft\Workbench\Parameters\Dir::adjustPath($this->workbenchSettings->requested["dir"]['valore'].$this->workbenchSettings->requested["domain"]['valore']).'www/.git/hooks/pre-commit');
            }
            if(substr($this->workbenchSettings->requested["type"]['valore'],-7) == 'package') {
                File::copy($dir,\Padosoft\Workbench\Parameters\Dir::adjustPath($this->workbenchSettings->requested["dir"]['valore'].$this->workbenchSettings->requested["domain"]['valore']).'.git/hooks/pre-commit');
            }

        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
            exit();
        }
        return true;
    }

    private function addHooks() {

        $dir = \Padosoft\Workbench\Parameters\Dir::adjustPath(__DIR__).'config/pre-commit';
        if(!File::exists($dir)) {
            $this->error('File '.$dir.' not exist');
            exit();
        }

        if(substr($this->workbenchSettings->requested["type"]['valore'],-7) != 'package') {
            File::copy($dir,\Padosoft\Workbench\Parameters\Dir::adjustPath($this->workbenchSettings->requested["dir"]['valore'].$this->workbenchSettings->requested["domain"]['valore']).'www/.git/hooks/pre-commit');
        }
        if(substr($this->workbenchSettings->requested["type"]['valore'],-7) == 'package') {
            File::copy($dir,\Padosoft\Workbench\Parameters\Dir::adjustPath($this->workbenchSettings->requested["dir"]['valore'].$this->workbenchSettings->requested["domain"]['valore']).'.git/hooks/pre-commit');
        }
    }

    private function createDomainFolder()
    {
        if(File::exists($this->workbenchSettings->requested["dir"]['valore'].$this->workbenchSettings->requested["domain"]['valore'])){
            $this->error("Domain directory exist.");
            exit();
        }

        $result = File::makeDirectory($this->workbenchSettings->requested["dir"]['valore'].$this->workbenchSettings->requested["domain"]['valore'],493,true);
        if(substr($this->workbenchSettings->requested["type"]['valore'],-7) != 'package') {
            File::makeDirectory(Parameters\Dir::adjustPath($this->workbenchSettings->requested["dir"]['valore'].$this->workbenchSettings->requested["domain"]['valore']).'www/',493,true);
            File::makeDirectory(Parameters\Dir::adjustPath($this->workbenchSettings->requested["dir"]['valore'].$this->workbenchSettings->requested["domain"]['valore']).'apache2_logs/',493,true);
        }

        if(substr($this->workbenchSettings->requested["type"]['valore'],-7) == 'package') {
            File::makeDirectory(Parameters\Dir::adjustPath(Config::get('workbench.diraccess.'.$this->workbenchSettings->requested["dirtype"]['valore'].'.doc').$this->workbenchSettings->requested["organization"]['valore']).$this->workbenchSettings->requested["domain"]['valore'],493,true);
            //File::makeDirectory(Parameters\Dir::adjustPath(Config::get('workbench.diraccess.'.$this->workbenchSettings->requested["dirtype"]['valore'].'.doc').$this->workbenchSettings->requested["organization"]['valore']).$this->workbenchSettings->requested["domain"]['valore'].'/dev-master',493,true);
            //File::makeDirectory(Parameters\Dir::adjustPath(Config::get('workbench.diraccess.'.$this->workbenchSettings->requested["dirtype"]['valore'].'.doc').$this->workbenchSettings->requested["organization"]['valore']).$this->workbenchSettings->requested["domain"]['valore'].'/resources',493,true);
        }


        $this->info("Domain dir created at ".$this->workbenchSettings->requested["dir"]['valore'].$this->workbenchSettings->requested["domain"]['valore']);
    }

    private function checkRepoGithubExist()
    {
        $httphelper = new HttpHelper(new HTTPClient(new Client(),new RequestHelper()));
        $response = $httphelper->sendGet("https://api.github.com/repos/". $this->workbenchSettings->requested["organization"]["valore"] ."/".$this->workbenchSettings->requested["packagename"]["valore"]);
        return ($response->status_code==200 ? true : false);
    }    
    
    private function checkRepoBitbucketExist()
    {
        $httphelper = new HttpHelper(new HTTPClient(new Client(),new RequestHelper()));
        //$response = $httphelper->sendGet("https://api.bitbucket.org/2.0/repositories/". $this->workbenchSettings->requested["organization"]['valore'] ."/".$this->workbenchSettings->requested["domain"]['valore']);
        $response = $httphelper->sendGetWithAuth("https://api.bitbucket.org/2.0/repositories/". $this->workbenchSettings->requested["organization"]["valore"] ."/".$this->workbenchSettings->requested["packagename"]["valore"],[],[],$this->workbenchSettings->requested["user"]["valore"],$this->workbenchSettings->requested["password"]["valore"]);
        return ($response->status_code==200 ? true : false);
    }
    
    private function createRepoGithub()
    {
        $response = new \Padosoft\HTTPClient\Response();
        try {
            $httphelper = new HttpHelper(new HTTPClient(new Client(),new RequestHelper()));
            $response = $httphelper->sendPostJsonWithAuth("https://api.github.com/orgs/".$this->workbenchSettings->requested['organization']['valore']."/repos",['name'=>$this->workbenchSettings->requested["packagename"]["valore"]],$this->workbenchSettings->requested["user"]["valore"],$this->workbenchSettings->requested["password"]["valore"]);
            $this->info("Created repo https://github.com/".$this->workbenchSettings->requested['organization']["valore"]."/".$this->workbenchSettings->requested["packagename"]["valore"]);
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
            $response = $httphelper->sendPostJsonWithAuth("https://api.bitbucket.org/2.0/repositories/".$this->workbenchSettings->requested['organization']['valore']."/".$this->workbenchSettings->requested['packagename']['valore'],['scm'=>'git', 'is_private'=>'true', 'name'=>$this->workbenchSettings->requested["packagename"]["valore"]],$this->workbenchSettings->requested["user"]["valore"],$this->workbenchSettings->requested["password"]["valore"]);
            $this->info("Created repo https://bitbucket.org/".$this->workbenchSettings->requested['organization']["valore"]."/".$this->workbenchSettings->requested["packagename"]["valore"]);
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

        if($this->workbenchSettings->requested["git"]["valore"]==Parameters\Git::GITHUB) {
            if ($this->workbenchSettings->requested["gitaction"]["valore"]==Parameters\GitAction::PUSH) {
                if($this->checkRepoGithubExist()) {
                    $this->error("Repo already exist!");
                    exit();
                }
                $this->createRepoGithub();
            }
            if ($this->workbenchSettings->requested["gitaction"]["valore"]==Parameters\GitAction::PULL) {
                if(!$this->checkRepoGithubExist()) {
                    $this->error("Repo not exist!");
                    exit();
                }

            }

        }

        if($this->workbenchSettings->requested["git"]["valore"]==Parameters\Git::BITBUCKET) {

            if ($this->workbenchSettings->requested["gitaction"]["valore"]==Parameters\GitAction::PUSH) {
                if($this->checkRepoBitbucketExist()) {
                    $this->error("Repo already exist!");
                    exit();
                }
                $this->createRepoBitBucket();
            }
            if ($this->workbenchSettings->requested["gitaction"]["valore"]==Parameters\GitAction::PULL) {
                if(!$this->checkRepoBitbucketExist()) {
                    $this->error("Repo not exist!");
                    exit();
                }
            }
        }


        $this->createAndDownloadFromGit($this->workbenchSettings->requested["git"]["valore-valido"]);
    }

    private function createVirtualhost(bool $silent,bool $filehosts)
    {
        $this->info("Creating virtualhost");
        $apachedir=Parameters\Dir::adjustPath(Config::get('workbench.diraccess.'.$this->workbenchSettings->requested['dirtype']['valore'].'.apache2'));
        $rootdir=Parameters\Dir::adjustPath($apachedir.$this->workbenchSettings->requested['domain']['valore']);
        $webdir=$rootdir.'www/';

        if($this->workbenchSettings->requested['type']['valore']=='laravel') {
            $webdir=$webdir.'public/';
        }

        $ssh = new SSH2($this->workbenchSettings->requested['sshhost']['valore']);
        if (!$ssh->login($this->workbenchSettings->requested['sshuser']['valore'], $this->workbenchSettings->requested['sshpassword']['valore'])) {
            if($silent) {
                exit('SSH login failed at '.$this->workbenchSettings->requested['sshuser']['valore'].'@'.$this->workbenchSettings->requested['sshhost']['valore']);
            }
            exit('SSH login failed at '.$this->workbenchSettings->requested['sshuser']['valore'].'@'.$this->workbenchSettings->requested['sshhost']['valore']);
        }
        //$ssh->read('/presente/',SSH2::READ_REGEX);
        if($ssh->exec("if [ -e /etc/apache2/sites-available/" . $this->workbenchSettings->requested['domain']['valore'].".conf ]; then echo 'presente'; else echo 'assente'; fi;")=="presente\n") {
            $this->error('File /etc/apache2/sites-available/'.$this->workbenchSettings->requested['domain']['valore'].'.conf exist');
            exit('File /etc/apache2/sites-available/'.$this->workbenchSettings->requested['domain']['valore'].'.conf exist');
        }

        $virtualhost = "
		<VirtualHost *:80>
			ServerAdmin ".$this->workbenchSettings->requested['email']['valore']."
			ServerName ".$this->workbenchSettings->requested['domain']['valore']."
			ServerAlias ".$this->workbenchSettings->requested['domain']['valore']."
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

        $ssh->exec('echo "'.$virtualhost.'" > /etc/apache2/sites-available/'.$this->workbenchSettings->requested['domain']['valore'].'.conf');
        $ssh->exec('a2ensite '.$this->workbenchSettings->requested['domain']['valore']);
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
            $ssh->exec('127.0.0.1	'.$this->workbenchSettings->requested['domain']['valore'].'>/etc/hosts');
        }
        $this->info("Host added");
    }

    private function removeToFileHosts(SSH2 $ssh)
    {
        $this->info("Remove from host");
        $ssh->exec('sed -i "/'.$this->workbenchSettings->requested['domain']['valore'].'/d" /etc/hosts');
        $this->info("Host removed");
    }



    private function deleteVirtualHost($filehosts)
    {
        $this->info("Deleting virtualhost");
        $this->error('Attenzione il virtual host di '.$this->workbenchSettings->requested['domain']['valore']+' verrà eliminato.');
        $apachedir="/var/www/html/";

        $ssh = new SSH2($this->workbenchSettings->requested['sshhost']['valore']); //ToDo
        if (!$ssh->login($this->workbenchSettings->requested['sshuser']['valore'], $this->workbenchSettings->requested['sshpassword']['valore'])) {
            exit('SSH login failed at '.$this->workbenchSettings->requested['sshuser']['valore'].'@'.$this->workbenchSettings->requested['sshuser']['valore']);
        }

        $ssh->exec('a2dissite '.$this->workbenchSettings->requested['domain']['valore']);
        $ssh->exec('/etc/init.d/apache2 reload');
        $ssh->exec('rm /etc/apache2/sites-available/'.$this->workbenchSettings->requested['domain']['valore'].'.conf');
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
        $packagename = $this->workbenchSettings->requested['packagename']['valore'];
        $packagedescr = $this->workbenchSettings->requested['packagedescr']['valore'];
        $packagekeywords = $this->workbenchSettings->requested['packagekeywords']['valore'];
        $organization = $this->workbenchSettings->requested['organization']['valore'];
        $doc_destination = \Padosoft\Workbench\Parameters\Dir::adjustPath(Config::get('workbench.diraccess.'.$this->workbenchSettings->requested['dirtype']['valore'].'.doc').$this->workbenchSettings->requested['organization']['valore']).$this->workbenchSettings->requested['domain']['valore'];

        $files = explode(",",Config::get('workbench.substitute.files'));

        foreach($files as $file) {

            $fileandpath=$file;
            if(substr($file,0,1)!="/") {

                $fileandpath=$this->workbenchSettings->requested['dir']['valore'].$this->workbenchSettings->requested['domain']['valore']."/".$file;
                if(substr($this->workbenchSettings->requested["type"]['valore'],-7) != 'package') {
                    $fileandpath=$this->workbenchSettings->requested['dir']['valore'].$this->workbenchSettings->requested['domain']['valore']."/www/".$file;
                }
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
                $str=str_replace("@@@doc_destination", $doc_destination,$str);



                file_put_contents($fileandpath, $str);
            } catch (\Exception $ex)  {

            }
        }
        $this->info("Changing complete");
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
        $index = str_replace('@@@package_name', $this->workbenchSettings->requested['packagename']['valore'],$index);
        $readme = file_get_contents($readmepathsource);
        $converter = new CommonMarkConverter();
        $index = str_replace("@@@readme", $converter->convertToHtml($readme),$index);
        $documentation="<h1>API Documentation</h1>
<p>Please see API documentation at http://".$this->workbenchSettings->requested['organization']['valore'].".github.io/".$this->workbenchSettings->requested['packagename']['valore']."</p>";
        $documentation_mod = "<a name=api-documentation ></a>"."<h1>API Documentation</h1>
<p>Please see API documentation at <a href ='http://".$this->workbenchSettings->requested['organization']['valore'].".github.io/".$this->workbenchSettings->requested['packagename']['valore']."'>".$this->workbenchSettings->requested['packagename']['valore']."</a></p>";

        $destination = File::dirname($readmepathdestination);
        //$list = array_diff(File::directories($destination),array($destination.'\resources'));
        //$list = array_diff($list,array($destination.'/resources'));
        $documentation_mod = $documentation_mod."<ul>";
        //foreach ($list as $tag) {
        //    $tag = File::basename(\Padosoft\Workbench\Parameters\Dir::adjustPath($tag));
            $documentation_mod = $documentation_mod."<li><a href = 'https://".$this->workbenchSettings->requested['organization']['valore'].".github.io/".$this->workbenchSettings->requested['packagename']['valore']."/master/build/'>master</a></li>";
        //}
        $documentation_mod = $documentation_mod."</ul>";
        $index = str_replace($documentation, $documentation_mod,$index);

        file_put_contents($readmepathdestination, $index);

    }

    public function setWorkbenchSettings($workbenchSettings)
    {
        $this->workbenchSettings=$workbenchSettings;
    }

    public function getWorkbenchSettings()
    {
        return $this->workbenchSettings;
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

