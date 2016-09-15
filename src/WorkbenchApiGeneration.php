<?php/** * Created by PhpStorm. * User: Alessandro * Date: 02/09/2016 * Time: 12:04 */namespace Padosoft\Workbench;use Padosoft\Workbench\WorkbenchSettings;use Illuminate\Console\Command;use File;use Config;use League\CommonMark\CommonMarkConverter;use GitWrapper\GitWrapper;class WorkbenchApiGeneration{    private $workbenchSettings;    private $command;    public function __construct(WorkbenchSettings $workbenchSettings, Command $command){        $this->workbenchSettings = $workbenchSettings;        $this->command = $command;    }    public function apigeneration()    {        $source = \Padosoft\Workbench\Parameters\Dir::adjustPath($this->workbenchSettings->requested['dir']['valore'].$this->workbenchSettings->requested['domain']['valore']);        $destination = \Padosoft\Workbench\Parameters\Dir::adjustPath(Config::get('workbench.diraccess.'.$this->workbenchSettings->requested['dirtype']['valore'].'.doc').$this->workbenchSettings->requested['organization']['valore']).$this->workbenchSettings->requested['domain']['valore'];        exec(Config::get('workbench.php_path').' '.\Padosoft\Workbench\Parameters\Dir::adjustPath(Config::get('workbench.common_dev_lib_path')).'apigen.phar generate --source '.$source.' --destination '.$destination.'/dev-master');        File::copyDirectory($destination.'/dev-master/resources/', $destination.'/resources/');        $readmepathsource = \Padosoft\Workbench\Parameters\Dir::adjustPath($source).'readme.md';        $readmepathdestination = \Padosoft\Workbench\Parameters\Dir::adjustPath($destination).'index.html';        $this->transformReadmeMd($readmepathsource, $readmepathdestination);        $gitWrapper = new GitWrapper();        $gitWorkingCopy=$gitWrapper->init($destination,[]);        $gitWrapper->git("config --global user.name ".$this->workbenchSettings->requested['user']['valore']);        $gitWrapper->git("config --global user.email ".$this->workbenchSettings->requested['email']['valore']);        $gitWrapper->git("config --global user.password ".$this->workbenchSettings->requested['password']['valore']);        $extension = ($this->workbenchSettings->requested["git"]["valore"]==Parameters\Git::BITBUCKET ? "org" : "com");        $gitWorkingCopy->addRemote('origin',"https://".$this->workbenchSettings->requested['user']['valore'].":".$this->workbenchSettings->requested['password']['valore']."@".$this->workbenchSettings->requested["git"]["valore"].".". $extension ."/".$this->workbenchSettings->requested['organization']['valore']."/".$this->workbenchSettings->requested['packagename']['valore'].".git" );        $gitWorkingCopy->checkoutNewBranch('gh-pages');        $gitWorkingCopy->add('.');        $gitWorkingCopy->commit('Workbench commit');        $gitWorkingCopy->push('origin','gh-pages');    }    public function apiSamiGeneration()    {        $source = \Padosoft\Workbench\Parameters\Dir::adjustPath($this->workbenchSettings->requested['dir']['valore'].$this->workbenchSettings->requested['domain']['valore']);        $destination = \Padosoft\Workbench\Parameters\Dir::adjustPath(Config::get('workbench.diraccess.'.$this->workbenchSettings->requested['dirtype']['valore'].'.doc').$this->workbenchSettings->requested['organization']['valore']).$this->workbenchSettings->requested['domain']['valore'];        echo Config::get('workbench.php_path').' '.\Padosoft\Workbench\Parameters\Dir::adjustPath(Config::get('workbench.common_dev_lib_path')).'sami.phar update '.$source.'sami_config.php';        exec(Config::get('workbench.php_path').' '.\Padosoft\Workbench\Parameters\Dir::adjustPath(Config::get('workbench.common_dev_lib_path')).'sami.phar update '.$source.'sami_config.php');        $readmepathsource = \Padosoft\Workbench\Parameters\Dir::adjustPath($source).'readme.md';        $readmepathdestination = \Padosoft\Workbench\Parameters\Dir::adjustPath($destination).'index.html';        $this->transformReadmeMd($readmepathsource, $readmepathdestination);        $gitWrapper = new GitWrapper();        $gitWorkingCopy=$gitWrapper->init($destination,[]);        $gitWrapper->git("config --global user.name ".$this->workbenchSettings->requested['user']['valore']);        $gitWrapper->git("config --global user.email ".$this->workbenchSettings->requested['email']['valore']);        $extension = ($this->workbenchSettings->requested["git"]["valore"]==Parameters\Git::BITBUCKET ? "org" : "com");        if(in_array('origin',$gitWorkingCopy->getRemotes())) {            $gitWorkingCopy->removeRemote('origin');        }        $gitWorkingCopy->addRemote('origin',"https://".$this->workbenchSettings->requested['user']['valore'].":".$this->workbenchSettings->requested['password']['valore']."@".$this->workbenchSettings->requested["git"]["valore"].".". $extension ."/".$this->workbenchSettings->requested['organization']['valore']."/".$this->workbenchSettings->requested['packagename']['valore'].".git" );        $gitWorkingCopy->checkoutNewBranch('gh-pages');        $gitWorkingCopy->add('.');        $gitWorkingCopy->commit('Workbench commit');        $gitWorkingCopy->push('origin','gh-pages');    }    public function transformReadmeMd($readmepathsource,$readmepathdestination) {        if(!File::exists($readmepathsource)) {            $this->command->error('File '.$readmepathsource.' not exist');            exit();        }        $dir = \Padosoft\Workbench\Parameters\Dir::adjustPath(__DIR__).'resources/index.html';        if(!File::exists($dir)) {            $this->command->error('File '.$dir.' not exist');            exit();        }        File::copy($dir,$readmepathdestination);        $index = file_get_contents($readmepathdestination);        $index = str_replace('@@@package_name', $this->workbenchSettings->requested['packagename']['valore'],$index);        $readme = file_get_contents($readmepathsource);        $converter = new CommonMarkConverter();        $index = str_replace("@@@readme", $converter->convertToHtml($readme),$index);        $documentation="<h1>API Documentation</h1><p>Please see API documentation at http://".$this->workbenchSettings->requested['organization']['valore'].".github.io/".$this->workbenchSettings->requested['packagename']['valore']."</p>";        $documentation_mod = "<a name=api-documentation ></a>"."<h1>API Documentation</h1><p>Please see API documentation at <a href ='http://".$this->workbenchSettings->requested['organization']['valore'].".github.io/".$this->workbenchSettings->requested['packagename']['valore']."/master/build/'>".$this->workbenchSettings->requested['packagename']['valore']."</a></p>";        $destination = File::dirname($readmepathdestination);        $documentation_mod = $documentation_mod."<ul>";        $documentation_mod = $documentation_mod."<li><a href = 'https://".$this->workbenchSettings->requested['organization']['valore'].".github.io/".$this->workbenchSettings->requested['packagename']['valore']."/master'>master</a></li>";        $documentation_mod = $documentation_mod."</ul>";        $index = str_replace($documentation, $documentation_mod,$index);        file_put_contents($readmepathdestination, $index);    }}