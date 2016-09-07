<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 02/09/2016
 * Time: 12:04
 */

namespace Padosoft\Workbench;

use Padosoft\Workbench\WorkbenchSettings;

class ApiGeneration
{

    private $workbenchSettings;

    public function __construct(WorkbenchSettings $workbenchSettings){
        $this->workbenchSettings = $workbenchSettings;
    }

    public function apigeneration()
    {
        $source = $this->workbenchSettings->requested['dir']['valore'].$this->workbenchSettings->requested['domain']['valore'];
        $destination = \Padosoft\Workbench\Parameters\Dir::adjustPath(Config::get('workbench.diraccess.'.$this->workbenchSettings->requested['dirtype']['valore'].'.doc').$this->workbenchSettings->requested['organization']['valore']).$this->workbenchSettings->requested['domain']['valore'];
        exec('C:/xampp/php/php.exe Y:/Public/common-dev-lib/apigen.phar generate --source '.$source.' --destination '.$destination.'/dev-master');

        File::copyDirectory($destination.'/dev-master/resources/', $destination.'/resources/');
        $readmepathsource = \Padosoft\Workbench\Parameters\Dir::adjustPath($source).'readme.md';
        $readmepathdestination = \Padosoft\Workbench\Parameters\Dir::adjustPath($destination).'index.html';
        $this->transformReadmeMd($readmepathsource, $readmepathdestination);

        $gitWrapper = new GitWrapper();
        $gitWorkingCopy=$gitWrapper->init($destination,[]);
        $gitWrapper->git("config --global user.name ".$this->workbenchSettings->requested['user']['valore']);
        $gitWrapper->git("config --global user.email ".$this->workbenchSettings->requested['email']['valore']);
        $gitWrapper->git("config --global user.password ".$this->workbenchSettings->requested['password']['valore']);
        $extension = ($this->workbenchSettings->requested["git"]["valore"]==Parameters\Git::BITBUCKET ? "org" : "com");
        $gitWorkingCopy->addRemote('origin',"https://".$this->workbenchSettings->requested['user']['valore'].":".$this->workbenchSettings->requested['password']['valore']."@".$this->workbenchSettings->requested["git"]["valore"].".". $extension ."/".$this->workbenchSettings->requested['organization']['valore']."/".$this->workbenchSettings->requested['packagename']['valore'].".git" );
        $gitWorkingCopy->checkoutNewBranch('gh-pages');
        $gitWorkingCopy->add('.');
        $gitWorkingCopy->commit('Workbench commit');
        $gitWorkingCopy->push('origin','gh-pages');

    }


}