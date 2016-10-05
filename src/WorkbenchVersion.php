<?php

namespace Padosoft\Workbench;


use Illuminate\Console\Command;
use GitWrapper\GitWrapper;
use GitWrapper\GitWorkingCopy;
use GitWrapper\GitBranches;
use File;
use Padosoft\Io\DirHelper;
use Padosoft\Workbench\WorkbenchApiGeneration;
use Padosoft\Workbench\WorkbenchSettings;

class WorkbenchVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workbench:version
                            {dir? : package dir}
                            {--u|user= : git user}
                            {--p|password= : git password}
                            {--e|email= : git email}
                            {--s|silent : no questions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = <<<EOF
The <info>workbench:version</info> ....
EOF;

    private $BASE_PATH;
    private $ORGANIZATION_PATH;
    private $parameters = array();
    private $domain;
    private $organization;
    private $packagename;
    private $workbenchSettings;
    private $type;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {



        $this->BASE_PATH=$this->argument("dir");
        if(empty($this->argument("dir"))) {
            $this->BASE_PATH=__DIR__;

        }
        $this->BASE_PATH=\Padosoft\Workbench\Parameters\Dir::adjustPath($this->BASE_PATH);
        $this->domain = basename($this->BASE_PATH);
        $this->ORGANIZATION_PATH = \Padosoft\Workbench\Parameters\Dir::adjustPath(substr($this->BASE_PATH,0,strlen($this->BASE_PATH)-(strlen($this->domain)+1)));
        $json = json_decode(file_get_contents($this->BASE_PATH."composer.json"),true);
        $this->organization = explode("/",$json["name"])[0];
        $this->packagename = explode("/",$json["name"])[1];
        $this->type = (in_array("public",explode("/",strtolower($this->BASE_PATH)))?"public":"private");

        $this->hardWork($this->argument(), $this->option());
    }

    /**
     * @param $argument
     * @param $option
     */
    private function hardWork($argument, $option)
    {

        $command = $this;
        $this->workbenchSettings = new WorkbenchSettings($command);

        $this->workbenchSettings->prepare($this->input->getOption("user"),"user");
        $this->workbenchSettings->prepare($this->input->getOption("password"),"password");
        $this->workbenchSettings->prepare($this->input->getOption("email") ,"email");
        $this->workbenchSettings->prepare($this->domain,"domain");
        $this->workbenchSettings->prepare("public","dirtype");
        $this->workbenchSettings->prepare($this->ORGANIZATION_PATH ,"dir");
        $this->workbenchSettings->prepare("github","git");
        $this->workbenchSettings->prepare($this->organization,"organization");
        $this->workbenchSettings->prepare($this->packagename,"packagename");



        $user = new Parameters\User($command);
        $user->read(false);
        $password = new Parameters\Password($command);
        $password->read(false);
        $email = new Parameters\Email($command);
        $email->read(false);

        $gitWrapper = new GitWrapper();
        $gitWorkingCopy = $gitWrapper->workingCopy($this->BASE_PATH);


        $gitWorkingCopy->remote("update");
        $commitControl = $gitWorkingCopy->status("-uno");

        preg_match('(Your branch is ahead|Your branch is up-to-date)',$commitControl,$matches);
        if(count($matches)==0) {
            echo "The local commit isn't update with remote commit";
            exit();
        }
        $this->line($matches[0]);

        $activebranch = $this->getActiveBranch($gitWrapper);

        //$message="Test package";
        do {
            $message = $this->ask("Commit message");
        } while ($message == "");

        $this->info("Active branch is ".$activebranch);
        $gitWrapper->git("add .");
        $this->addAndCommit($gitWorkingCopy,$message);



        $gitWrapper->git("config --global user.name alevento");
        $gitWrapper->git("config --global user.email alessandro.manneschi@gmail.com");




        $this->line("Last tag version is ". $this->getLastTagVersion($gitWrapper));

        //$tagVersion = array("0","0","0");

        $tagVersion = $this->getLastTagVersionArray($gitWrapper);
        $this->createSemverCopyFolder($gitWrapper);
        //$output = array();
        $output = $this->runSemVer();

        $this->line(implode("\r\n",$output));
        $this->warn("Semver output will be saved in ".sys_get_temp_dir()."/semver_output".date("Y-m-d").".txt");

        file_put_contents(sys_get_temp_dir()."/semver_output".date("Y-m-d").".txt",implode("\r\n",$output));
        $semVerVersion = $this->semVerAnalisys($output);
        $this->info("Suggested semantic versioning change: ". $semVerVersion);

        switch ($semVerVersion) {
            case "MAJOR";
                $tagVersion[0] = $tagVersion[0] +1;
                $tagVersion[1] = 0;
                $tagVersion[2] = 0;
                break;
            case "MINOR";
                $tagVersion[1] = $tagVersion[1] +1;
                $tagVersion[2] = 0;
                break;
            case "PATCH";
                $tagVersion[2] = $tagVersion[2] +1;
                break;
            default:
                return;
            break;
            }

        $this->error("Suggested TAG: ". implode(".",$tagVersion));


        $changelog = new \Padosoft\Workbench\WorkbenchChangelog($this->workbenchSettings,$this);
        $changelog->question()->getChanges();
        $changelog->writeChangeLog($this->BASE_PATH."CHANGELOG.md",implode(".",$tagVersion));

        $gitWrapper->git("add .");
        $this->addAndCommit($gitWorkingCopy,"Changelog updated");

        $tagged=false;
        if ($this->confirm("Do you want tag the active branch?")) {
            $this->tagActiveBranch($gitWorkingCopy,implode(".",$tagVersion));
            $tagged=true;
        }

        if ($this->confirm("Do you want push the active branch?")) {
            $this->pushOriginActiveBranch($gitWorkingCopy,$activebranch);
            $this->line("Active branch pushed on origin");
            if($tagged) {
                $this->pushTagOriginActiveBranch($gitWorkingCopy,implode(".",$tagVersion));
                $this->line("Tagged");
            }


        }



        /*
        if ($pushed) {
            $this->pushTagOriginActiveBranch($gitWorkingCopy,implode(".",$tagVersion));
            $this->line("Tagged");
        }*/


        $apiSamiGeneration = new WorkbenchApiGeneration($this->workbenchSettings,$this);
        $apiSamiGeneration->apiSamiGeneration();



        //TODO
        //chiedere messaggio di commit*
        //commit del progetto*
        //mostrare branch attivo*
        //una volta committato fare pull del progetto
        //mostrare il risultato del pull evidenziando parole tipo automerge e fail
        //chiedere se continuare
        //trovare l'ultimo tag del progetto
        //se si continua creare 2 copie del progetto, fare checkout di una delle 2 all'ultimo tag di versione
        //lanciare il confronto, evidenziare il tipo di cambiamento e suggerire la versione
        //chiedere se pushare e taggare con la nuova versione

    }





    public function runSemVer()
    {
        $output = array();
        $rawoutput = exec('C:/xampp/php/php.exe Y:/Public/common-dev-lib/php-semver-checker.phar compare y:/semver/oldversion y:/semver/original',$output);

        return $output;

    }

    public function semVerAnalisys(array $output)
    {
        $positionVersion = strpos($output[2],":")+2;
        return substr($output[2],$positionVersion,strlen($output[2])-$positionVersion);
    }

    public function getListBranches(GitWorkingCopy $gitWorkingCopy)
    {
        $gitbranches = new GitBranches($gitWorkingCopy);
        $branches = array();
        return $gitbranches->fetchBranches();
    }

    public function getActiveBranch(GitWrapper $gitWrapper)
    {
        $status=$gitWrapper->git("status");
        return substr($status,10,strpos($status,"\n")-10);
    }

    public function getLastTagVersionArray(GitWrapper $gitWrapper)
    {

        $lastlocaltag = $this->getLastTagVersion($gitWrapper);

        if(starts_with($lastlocaltag,"v")) {
            $lastlocaltag = str_replace("\n","",substr($lastlocaltag,1));
        }
        return explode(".", rtrim($lastlocaltag));

    }

    public function getLastTagVersion(GitWrapper $gitWrapper)
    {
        $tags=$gitWrapper->git("tag");
        $lastlocaltag = "";
        if($tags == "") {
            return;
        }

        return  trim(preg_replace('/\s\s+/', '', $gitWrapper->git("describe --abbrev=0 --tags")));

    }

    public function createSemverCopyFolder(GitWrapper $gitWrapper)
    {
        if(File::exists("y:/semver/original/"))
        {
            File::deleteDirectory("y:/semver/original/");
        }
        if(File::exists("y:/semver/oldversion/"))
        {
            File::deleteDirectory("y:/semver/oldversion/");
        }

        if(!File::exists("y:/semver/original/"))
        {
            File::makeDirectory("y:/semver/original/",493,true);
        }
        if(!File::exists("y:/semver/oldversion/"))
        {
            File::makeDirectory("y:/semver/oldversion/",493,true);
        }

        $this->line('inizio copia');

        $dir = new \DirectoryIterator($this->BASE_PATH);
        $file = new \FilesystemIterator($this->BASE_PATH);


        DirHelper::copy($this->BASE_PATH,"y:/semver/original/",[$this->BASE_PATH."vendor"]);
        DirHelper::copy($this->BASE_PATH,"y:/semver/oldversion/",[$this->BASE_PATH."vendor"]);

        $this->line('finito copia');
        //File::copyDirectory($this->BASE_PATH,"y:/semver/original/");
        //File::copyDirectory($this->BASE_PATH,"y:/semver/oldversion/");
        $lastTagVersion = $this->getLastTagVersion($gitWrapper);

        $gitWorkingCopySemver = $gitWrapper->workingCopy("y:/semver/oldversion/");
        return $this->checkoutToTagVersion($lastTagVersion,$gitWorkingCopySemver);


    }

    public function checkoutToTagVersion($version,GitWorkingCopy $gitWorkingCopySemver)
    {
        return $gitWorkingCopySemver->checkout($version);

    }

    public function addAndCommit(GitWorkingCopy $gitWorkingCopy, $message)
    {
        $output = $gitWorkingCopy->status(array("porcelain"=>true));
        if($output=="")
        {
            return;
        }
        //$gitWorkingCopy->add("/.");
        return $gitWorkingCopy->commit($message)->getStatus();
    }

    public function pushOriginActiveBranch(GitWorkingCopy $gitWorkingCopy,$branch)
    {
        return $gitWorkingCopy->push("https://". $this->workbenchSettings->requested['user']['valore'] .":". $this->workbenchSettings->requested['password']['valore'] ."@github.com/padosoft/workbench.git",$branch);

    }

    public function tagActiveBranch(GitWorkingCopy $gitWorkingCopy, $tag)
    {
        return $gitWorkingCopy->tag($tag);
    }

    public function pushTagOriginActiveBranch(GitWorkingCopy $gitWorkingCopy, $tag)
    {
        return $gitWorkingCopy->pushTag($tag,"https://". $this->workbenchSettings->requested['user']['valore'] .":". $this->workbenchSettings->requested['password']['valore'] ."@github.com/padosoft/workbench.git");

    }

    public function pullOriginMaster(GitWorkingCopy $gitWorkingCopy)
    {
        return $gitWorkingCopy->pull('origin','master');
    }

    public function pullOriginActiveBranch(GitWorkingCopy $gitWorkingCopy,$branch)
    {
        return $gitWorkingCopy->pull("origin",$branch);

    }

    public function formatColorRedText($testo)
    {
        $testo = str_replace("fail","<error>fail</error>",$testo);
        $testo = str_replace("merge","<error>merge</error>",$testo);
        $testo = str_replace("tracked ","<error>tracked </error>",$testo);

        return $testo;
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

