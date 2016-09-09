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
    protected $signature = 'workbench:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = <<<EOF
The <info>workbench:version</info> ....
EOF;

    private  $BASE_PATH = "Y:/Public/laravel-packages/www/laravel/5.2.x/packages/Padosoft/workbench/";

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
        $gitWrapper = new GitWrapper();
        $gitWorkingCopy = $gitWrapper->workingCopy($this->BASE_PATH);

        $branches = $this->getListBranches($gitWorkingCopy);
        $activebranch = $this->getActiveBranch($gitWrapper);

        $message="Test package";
        /*do {
            $message = $this->ask("Commit message");
        } while ($message == "");*/

        $this->info("Active branch is ".$activebranch);
        $gitWrapper->git("add .");
        $this->line( $this->addAndCommit($gitWorkingCopy,$message));

        $gitWrapper->git("config --global user.name alevento");
        $gitWrapper->git("config --global user.email alessandro.manneschi@gmail.com");
        $gitWrapper->git("config --global user.password 129895ale");

        //$messagepull = $this->pullOriginActiveBranch($gitWorkingCopy,$activebranch);
        //$this->line($this->formatColorRedText($messagepull));
        /*if(!$this->ask("Do you want continue pushing and tagging project?","y"))
        {
            return;
        }*/
        $this->line("Last tag version is ". $this->getLastTagVersion($gitWrapper));

        //$tagVersion = array("0","0","0");

        $tagVersion = $this->getLastTagVersionArray($gitWrapper);
        $this->createSemverCopyFolder($gitWrapper);
        //$output = array();
        $output = $this->runSemVer();
        $this->line(implode(",",$output));
        $semVerVersion = $this->semVerAnalisys($output);
        $this->line("Suggested semantic versioning change: ". $semVerVersion);

        switch ($semVerVersion)
            {
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

        $this->line("Suggested TAG: ". implode(".",$tagVersion));

        $this->pushOriginActiveBranch($gitWorkingCopy,$activebranch);

        $this->line("Active branch pushed on origin");

        $this->tagActiveBranch($gitWorkingCopy,implode(".",$tagVersion));

        $this->pushTagOriginActiveBranch($gitWorkingCopy,implode(".",$tagVersion));

        $this->line("Tagged");

        $workbenchSettings = new WorkbenchSettings();


            $workbenchSettings->prepare("workbench","domain");
            $workbenchSettings->prepare("laravel_package","type");
            $workbenchSettings->prepare("public","dirtype");
            $workbenchSettings->prepare($this->BASE_PATH ,"dir");
            $workbenchSettings->prepare("github","git");
            $workbenchSettings->prepare("alevento","user");
            $workbenchSettings->prepare(env('PWD_ALE_GITHUB'),"password");
            $workbenchSettings->prepare("a@a.it","email");
            $workbenchSettings->prepare("padosoft","organization");

        $apiSamiGeneration = new WorkbenchApiGeneration($workbenchSettings,$this);
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
        return $gitWorkingCopy->push("https://alevento:129895ale@github.com/padosoft/workbench.git",$branch);

    }

    public function tagActiveBranch(GitWorkingCopy $gitWorkingCopy, $tag)
    {
        return $gitWorkingCopy->tag($tag);
    }

    public function pushTagOriginActiveBranch(GitWorkingCopy $gitWorkingCopy, $tag)
    {
        return $gitWorkingCopy->pushTag($tag,"https://alevento:129895ale@github.com/padosoft/workbench.git");

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


}

