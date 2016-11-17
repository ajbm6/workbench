<?php

namespace Padosoft\Workbench;


use Illuminate\Console\Command;
use GitWrapper\GitWrapper;
use GitWrapper\GitWorkingCopy;
use GitWrapper\GitBranches;
use File;
use Config;
use League\CLImate\TerminalObject\Dynamic\Padding;
use Padosoft\HTTPClient\HTTPClient;
use Padosoft\HTTPClient\HttpHelperFacade;
use Padosoft\HTTPClient\RequestHelper;
use Padosoft\Io\DirHelper;
use Padosoft\Workbench\WorkbenchApiGeneration;
use Padosoft\Workbench\WorkbenchSettings;
use Symfony\Component\Process\ExecutableFinder;
use Padosoft\Support;
use Padosoft\HTTPClient\HttpHelper;


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
    private $phpBinary;
    private $gitBinary;
    private $DEBUG = false;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $finder = new ExecutableFinder();
        $this->gitBinary = '"'.str_replace("\\","/",$finder->find('git')).'"';
        if (!$this->gitBinary) {
            throw new GitException('Unable to find the Git executable.');
        }

        $this->phpBinary = '"'.str_replace("\\","/",$finder->find('php')).'"';
        if (!$this->phpBinary) {
            throw new Exception('Unable to find the Php executable.');
        }

        //$gitWrapper = new GitWrapper();

        /*
        try {
            $output = $gitWrapper->git("rev-parse --quiet --verify gh-pages","Y:/Public/laravel-packages/www/doc/padosoft/workbench");
        }
        catch (\Exception $e) {
            $output = "";
            //dd($e->getMessage() ."\r\n". $e->getTraceAsString());
        }*/

        $this->BASE_PATH=$this->argument("dir");
        if(empty($this->argument("dir"))) {
            $this->BASE_PATH=base_path();

        }

        if(!is_dir($this->BASE_PATH)) {
            if(is_dir(base_path().'/'.$this->BASE_PATH)) {
                $this->BASE_PATH=base_path().'/'.$this->BASE_PATH;
            }
            else {
                exit();
            }
        }
        $this->BASE_PATH=\Padosoft\Workbench\Parameters\Dir::adjustPath($this->BASE_PATH);
        $this->line("Path analyzed: ". $this->BASE_PATH);
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

        //$this->workbenchSettings->prepare("github","git");
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

        //$gitWrapper = new GitWrapper();

        //$gitWorkingCopy = $gitWrapper->workingCopy($this->BASE_PATH);

        $gitSimpleWrapper = new GitSimpleWrapper($this->BASE_PATH,null);
        $gitSimpleWrapper->git("remote update");

        //$gitWorkingCopy->remote("update");
        //$commitControl = $gitWorkingCopy->status("-uno");

        $commitControl="";
        try  {
            $output =  $gitSimpleWrapper->git("status -uno");
            (is_array($output) ? $commitControl = implode("\r\n",$output):$commitControl=$output);
        }
        catch (\Exception $e)  {
            echo $e->getMessage();
        }
        $this->info($commitControl);
        preg_match("(Your branch is ahead|Your branch is up-to-date|Changes not staged for commit|nothing to commit)",$commitControl,$matches);
        if(count($matches)==0) {
            echo "The local commit isn't update with remote commit";
            exit();
        }
        $this->info($matches[0]);

        $activebranch = $this->getActiveBranch($gitSimpleWrapper);

        //$message="Test package";
        $message = "DEBUG";
        if(!$this->DEBUG) {
            do {
                $message = $this->ask("Commit message");
            } while ($message == "");
        }
        $this->info("Active branch is ".$activebranch);
        $gitSimpleWrapper->git("add .");
        $this->addAndCommit($gitSimpleWrapper,$message);


        $lastTag= $this->getLastTagVersion($gitSimpleWrapper);
        $this->line("Last tag version is ". $lastTag);

        $tagVersionOriginal[0] = 0;
        $tagVersionOriginal[1] = 0;
        $tagVersionOriginal[2] = 0;

        $tagVersion[0] = 0;
        $tagVersion[1] = 0;
        $tagVersion[2] = 1;

        if($lastTag!="0.0.0") {

            $tagVersion = $this->getLastTagVersionArray($gitSimpleWrapper);
            $this->createSemverCopyFolder($gitSimpleWrapper);
            //$output = array();
            $output = $this->runSemVer();

            $this->line(implode("\r\n",$output));
            $this->warn("Semver output will be saved in ".sys_get_temp_dir()."/semver_output".date("Y-m-d").".txt");

            file_put_contents(sys_get_temp_dir()."/semver_output".date("Y-m-d").".txt",implode("\r\n",$output));
            $semVerVersion = $this->semVerAnalisys($output);
            $this->info("Suggested semantic versioning change: ". $semVerVersion);

            $color = "";
            $tagVersionOriginal=$tagVersion;
            switch ($semVerVersion) {
                case "MAJOR":
                    $tagVersion[0] = $tagVersion[0] +1;
                    $tagVersion[1] = 0;
                    $tagVersion[2] = 0;
                    $color = "red";
                    break;
                case "MINOR":
                    $tagVersion[1] = $tagVersion[1] +1;
                    $tagVersion[2] = 0;
                    $color = "yellow";
                    break;
                case "PATCH":
                    $tagVersion[2] = $tagVersion[2] +1;
                    $color = "yellow";
                    break;
                case "NONE":
                    break;
                default:
                    return;
                break;
                }

            if($color == "red") {
                $this->error("Suggested TAG: ". implode(".",$tagVersion));
            }

            if($color == "yellow") {
                $this->info("Suggested TAG: ". implode(".",$tagVersion));
            }
        }




        $changelog = new \Padosoft\Workbench\WorkbenchChangelog($this->workbenchSettings,$this);
        $changelogChanges = $changelog->question()->getChanges();
        $changelog->writeChangeLog($this->BASE_PATH."CHANGELOG.md",implode(".",$tagVersion));

        $gitSimpleWrapper->git("add .");
        $gitSimpleWrapper->git('commit -m "Changelog updated"');


        $tagged=false;
        if ($this->confirm("Do you want tag the active branch?",true)) {
            do  {
                $typedTagVersion = $this->ask("Type the TAG you want to use, the correct format is '#.#.#'",implode(".",$tagVersion));
                $isValid = $this->validateTAG($typedTagVersion);
                $typedTagVersioneArray=array();
                if($isValid) {
                    $typedTagVersioneArray=explode(".",$typedTagVersion);
                    $tagValueTyped=$typedTagVersioneArray[0]*pow(10, 12)+$typedTagVersioneArray[1]*pow(10, 8)+$typedTagVersioneArray[2]*pow(10, 4);
                    $tagValue=$tagVersionOriginal[0]*pow(10, 12)+$tagVersionOriginal[1]*pow(10, 8)+$tagVersionOriginal[2]*pow(10, 4);
                    if($tagValueTyped<=$tagValue) {
                        $this->error("Type a tag with a value greater than the previous.");
                        $isValid=false;
                    }
                }
                if($isValid)  {

                    $tagVersion[0]=$typedTagVersioneArray[0];
                    $tagVersion[1]=$typedTagVersioneArray[1];
                    $tagVersion[2]=$typedTagVersioneArray[2];
                }
                if(!$isValid)  {
                    $this->error("Invalid value!");
                }

            } while(!$isValid);


            try {
                $this->tagActiveBranch($gitSimpleWrapper,implode(".",$tagVersion));
            }
            catch (\Exception $e) {

            }
            //
            $tagged=true;

        }

        if ($this->confirm("Do you want push the active branch?",true)) {
            $output=$this->pushOriginActiveBranch($gitSimpleWrapper,$activebranch);
            $output=str_replace($this->workbenchSettings->requested['password']['valore'],'******',$output);
            $this->line($output);
            $this->line("Active branch pushed on origin");
            if($tagged) {
                $this->pushTagOriginActiveBranch($gitSimpleWrapper,implode(".",$tagVersion));
                $toAddToFile="## ". implode(".",$tagVersion)." - ".date("Y-m-d")."\r\n";
                foreach ($changelogChanges as $key => $values) {
                    if(count($changelogChanges[$key])) {
                        $toAddToFile = $toAddToFile."\r\n";
                        $toAddToFile = $toAddToFile."### ".ucfirst($key)."\r\n";
                    }

                    foreach($changelogChanges[$key] as $change) {
                        $toAddToFile = $toAddToFile."- ".$change."\r\n";
                    }

                }
                $json = [
                    "tag_name"=>implode(".",$tagVersion),
                    "target_commitish"=>$activebranch,
                    "name"=>implode(".",$tagVersion),
                    "body"=>$toAddToFile,
                    "draft"=> false,
                    "prerelease"=>false
                ];

                $response = HttpHelperFacade::sendPostJsonWithAuth("https://api.github.com/repos/".$this->organization."/".$this->packagename."/releases",$json,$this->workbenchSettings->getRequested()["user"]["valore"],$this->workbenchSettings->getRequested()["password"]["valore"]);
                $response->psr7response;
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


    /**
     * @return array
     */
    public function runSemVer()
    {
        $output = array();
        $rawoutput = exec($this->phpBinary.' '.Config::get('workbench.common_dev_lib_path').'/php-semver-checker.phar compare '.sys_get_temp_dir().'/semver/oldversion '.sys_get_temp_dir().'/semver/original',$output);
        //TODO metti nel config il phar  del semver e le cartelle per original e oldversione
        return $output;

    }

    /**
     * @param array $output
     * @return string
     */
    public function semVerAnalisys(array $output)
    {
        $positionVersion = strpos($output[2],":")+2;
        return substr($output[2],$positionVersion,strlen($output[2])-$positionVersion);
    }

    /**
     * @param GitSimpleWrapper $gitSimpleWrapper
     * @return string
     */
    public function getActiveBranch(GitSimpleWrapper $gitSimpleWrapper)
    {
        $status=$gitSimpleWrapper->git("status");
        return substr($status[0],10,strlen($status[0])-10);
    }

    /**
     * @param GitSimpleWrapper $gitSimpleWrapper
     * @return array
     */
    public function getLastTagVersionArray(GitSimpleWrapper $gitSimpleWrapper)
    {

        $lastlocaltag = $this->getLastTagVersion($gitSimpleWrapper);

        if(starts_with($lastlocaltag,"v")) {
            $lastlocaltag = str_replace("\n","",substr($lastlocaltag,1));
        }
        return explode(".", rtrim($lastlocaltag));

    }

    /**
     * @param GitSimpleWrapper $gitSimpleWrapper
     */
    public function getLastTagVersion(GitSimpleWrapper $gitSimpleWrapper)
    {
        $tags=$gitSimpleWrapper->git("tag");
        //$lastlocaltag = "";

        if(empty($tags)) {
            return "0.0.0";
        }

        //return  trim(preg_replace('/\s\s+/', '', $gitSimpleWrapper->git("describe --abbrev=0 --tags")));
        return $tags[count($tags)-1];
    }

    /**
     * @param GitSimpleWrapper $gitSimpleWrapper
     * @return mixed
     */
    public function createSemverCopyFolder(GitSimpleWrapper $gitSimpleWrapper)
    {
        if(File::exists(sys_get_temp_dir()."/semver/original/"))
        {
            File::deleteDirectory(sys_get_temp_dir()."/semver/original/");
        }
        if(File::exists(sys_get_temp_dir()."/semver/oldversion/"))
        {
            File::deleteDirectory(sys_get_temp_dir()."/semver/oldversion/");
        }

        if(!File::exists(sys_get_temp_dir()."/semver/original/"))
        {
            File::makeDirectory(sys_get_temp_dir()."/semver/original/",493,true);
        }
        if(!File::exists(sys_get_temp_dir()."/semver/oldversion/"))
        {
            File::makeDirectory(sys_get_temp_dir()."/semver/oldversion/",493,true);
        }

        $this->line('Start copy');

        $dir = new \DirectoryIterator($this->BASE_PATH);
        $file = new \FilesystemIterator($this->BASE_PATH);


        DirHelper::copy($this->BASE_PATH,sys_get_temp_dir()."/semver/original/",[$this->BASE_PATH."vendor"]);
        DirHelper::copy($this->BASE_PATH,sys_get_temp_dir()."/semver/oldversion/",[$this->BASE_PATH."vendor"]);

        $this->line('End copy');
        //File::copyDirectory($this->BASE_PATH,"y:/semver/original/");
        //File::copyDirectory($this->BASE_PATH,"y:/semver/oldversion/");
        $lastTagVersion = $this->getLastTagVersion($gitSimpleWrapper);

        //$gitWorkingCopySemver = $gitSimpleWrapper->workingCopy("y:/semver/oldversion/");
        $workingDirectory = $gitSimpleWrapper->getWorkingDirectory();
        $gitSimpleWrapper->setWorkingDirectory(sys_get_temp_dir()."/semver/oldversion/");
        $output = $this->checkoutToTagVersion($lastTagVersion,$gitSimpleWrapper);
        $gitSimpleWrapper->setWorkingDirectory($workingDirectory);
        return $output;


    }

    /**
     * @param $version
     * @param GitSimpleWrapper $gitSimpleWrapper
     * @return mixed
     */
    public function checkoutToTagVersion($version,GitSimpleWrapper $gitSimpleWrapper)
    {
        return $gitSimpleWrapper->git("checkout ".$version);

    }

    /**
     * @param GitSimpleWrapper $gitSimpleWrapper
     * @param $message
     */
    public function addAndCommit(GitSimpleWrapper $gitSimpleWrapper, $message)
    {

        try {
            //$gitWorkingCopy->commit('Commit', array('m' => $message));
            $gitSimpleWrapper->git('commit -m "'.$message.'"');
        }
        catch (\Exception $e) {
            //$output = "";
        }

        //return $gitWorkingCopy->commit($message)->getStatus();
    }

    /**
     * @param GitSimpleWrapper $gitSimpleWrapper
     * @param $branch
     * @return mixed
     */
    public function pushOriginActiveBranch(GitSimpleWrapper $gitSimpleWrapper, $branch)
    {
        //return $gitWorkingCopy->push("https://". $this->workbenchSettings->requested['user']['valore'] .":". $this->workbenchSettings->requested['password']['valore'] ."@github.com/padosoft/workbench.git",$branch);
        return $gitSimpleWrapper->git("push https://". $this->workbenchSettings->requested['user']['valore'] .":". $this->workbenchSettings->requested['password']['valore'] ."@github.com/". $this->workbenchSettings->requested['organization']['valore'] ."/".$this->workbenchSettings->requested['packagename']['valore'].".git ".$branch);
    }

    /**
     * @param GitSimpleWrapper $gitSimpleWrapper
     * @param $tag
     * @return mixed
     */
    public function tagActiveBranch(GitSimpleWrapper $gitSimpleWrapper, $tag)
    {
        return $gitSimpleWrapper->git("tag ".$tag);
    }

    /**
     * @param GitSimpleWrapper $gitSimpleWrapper
     * @param $tag
     * @return mixed
     */
    public function pushTagOriginActiveBranch(GitSimpleWrapper $gitSimpleWrapper, $tag)
    {
        //return $gitWorkingCopy->pushTag($tag,"https://". $this->workbenchSettings->requested['user']['valore'] .":". $this->workbenchSettings->requested['password']['valore'] ."@github.com/padosoft/workbench.git");
        return $gitSimpleWrapper->git("push https://". $this->workbenchSettings->requested['user']['valore'] .":". $this->workbenchSettings->requested['password']['valore'] ."@github.com/". $this->workbenchSettings->requested['organization']['valore'] ."/".$this->workbenchSettings->requested['packagename']['valore'].".git ".$tag );
    }

    /**
     * @param GitSimpleWrapper $gitSimpleWrapper
     * @return mixed
     */
    public function pullOriginMaster(GitSimpleWrapper $gitSimpleWrapper)
    {
        return $gitSimpleWrapper->git("pull origin waster");
    }

    /**
     * @param GitSimpleWrapper $gitSimpleWrapper
     * @param $branch
     * @return mixed
     */
    public function pullOriginActiveBranch(GitSimpleWrapper $gitSimpleWrapper,$branch)
    {
        return $gitSimpleWrapper->git("pull origin ".$branch);

    }

    /**
     * @param $testo
     * @return mixed
     */
    public function formatColorRedText($testo)
    {
        $testo = str_replace("fail","<error>fail</error>",$testo);
        $testo = str_replace("merge","<error>merge</error>",$testo);
        $testo = str_replace("tracked ","<error>tracked </error>",$testo);

        return $testo;
    }

    /**
     * @param $workbenchSettings
     */
    public function setWorkbenchSettings($workbenchSettings)
    {
        $this->workbenchSettings=$workbenchSettings;
    }

    /**
     * @return mixed
     */
    public function getWorkbenchSettings()
    {
        return $this->workbenchSettings;
    }

    /**
     * @param $tag
     * @return bool
     */
    public function validateTAG($tag)
    {
        $this->line($tag);
        $tagArray=explode(".",$tag);

        if(count($tagArray)!=3) {
            return false;
        }
        if(!isIntegerPositiveOrZero($tagArray[0])) {
            return false;
        }
        if(!isIntegerPositiveOrZero($tagArray[1])) {
            return false;
        }
        if(!isIntegerPositiveOrZero($tagArray[2])) {
            return false;
        }
        return true;
    }


    /**
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    /**
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }


}

