<?php

namespace Padosoft\WorkbenchVersion;


use Illuminate\Console\Command;
use GitWrapper\GitWrapper;
use GitWrapper\GitWorkingCopy;
use GitWrapper\GitBranches;
use File;


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

        $this->runSemVer();
        $gitWrapper = new GitWrapper();
        $gitWorkingCopy = $gitWrapper->workingCopy(base_path());

        $branches = $this->getListBranches($gitWorkingCopy);
        $activebranch = $this->getActiveBranch($gitWrapper);



        //TODO
        //chiedere messaggio di commit
        //commit del progetto
        //mostrare branch attivo
        //una volta committato fare pull del progetto
        //mostrare il risultato del pull evidenziando parole tipo automerge e fail
        //chiedere se continuare
        //trovare l'ultimo tag del progetto
        //se si continua creare 2 copie del progetto, fare checkout di una delle 2 all'ultimo tag di versione
        //lanciare il confronto, evidenziare il tipo di cambiamento e suggerire la versione
        //chiedere se pushare e taggare con la nuova versione

        $version = $this->getLastTagVersionArray($gitWrapper);





    }


    public function createSemverCopyFolder(GitWrapper $gitWrapper)
    {
        File::copyDirectory("../".base_path(),"y:/semver/original");
        File::copyDirectory("y:/semver/original","y:/semver/oldversion");
        $lastTagVersion = $this->getLastTagVersion($gitWrapper);

        $gitWorkingCopySemver = $gitWrapper->workingCopy("y:/semver/oldversion");
        $this->checkoutToTagVersion($lastTagVersion,$gitWorkingCopySemver);


    }

    public function checkoutToTagVersion($version,GitWorkingCopy $gitWorkingCopySemver)
    {
        $gitWorkingCopySemver->checkout("checkout ".$version);
    }


    public function getLastTagVersionArray(GitWrapper $gitWrapper)
    {

        $lastlocaltag = $this->getLastTagVersion($gitWrapper);

        if(starts_with($lastlocaltag,"v")) {
            $lastlocaltag = str_replace("\n","",substr($lastlocaltag,1));
        }
        $version = explode(".", rtrim($lastlocaltag));
        return $version;
    }

    public function getLastTagVersion(GitWrapper $gitWrapper)
    {
        $tags=$gitWrapper->git("tag");
        $lastlocaltag = "";
        if($tags == "") {
            return;
        }

        return $gitWrapper->git("describe --abbrev=0 --tags");

    }

    public function getActiveBranch(GitWrapper $gitWrapper)
    {
        $status=$gitWrapper->git("status");
        return substr($status,10,strpos($status,"\n")-10);
    }

    public function getListBranches(GitWorkingCopy $gitWorkingCopy)
    {
        $gitbranches = new GitBranches($gitWorkingCopy);
        $branches = array();
        return $gitbranches->fetchBranches();
    }

    public function pullOriginMaster(GitWorkingCopy $gitWorkingCopy)
    {
        return $gitWorkingCopy->pull('origin','master');
    }

    public function formatColorRedText($testo)
    {
        $testo = str_replace("fail","<error>fail</error>",$testo);
        $testo = str_replace("merge","<error>merge</error>",$testo);
        $testo = str_replace("tracked ","<error>tracked </error>",$testo);

        return $testo;
    }

    public function runSemVer()
    {
        $output = array();
        $rawoutput = exec('C:/xampp/php/php.exe Y:/Public/common-dev-lib/php-semver-checker.phar compare y:/semver/oldversion y:/semver/original',$output);
        echo $rawoutput;
    }


}

