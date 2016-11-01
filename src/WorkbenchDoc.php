<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 31/10/2016
 * Time: 11:28
 */

namespace Padosoft\Workbench;


use Illuminate\Console\Command;
use GitWrapper\GitWrapper;
use GitWrapper\GitWorkingCopy;
use GitWrapper\GitBranches;
use File;
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


class WorkbenchDoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workbench:doc
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


        $apiSamiGeneration = new WorkbenchApiGeneration($this->workbenchSettings,$this);
        $apiSamiGeneration->apiSamiGeneration();


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

