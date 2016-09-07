<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 07/09/2016
 * Time: 10:30
 */

namespace Padosoft\Workbench;

use Illuminate\Console\Command;
use File;
use Padosoft\Workbench\WorkbenchSettings;
use League\CommonMark\CommonMarkConverter;


class TransformText
{

    private $command;
    private $workbenchSettings;

    public function __construct(Command $command, WorkbenchSettings $workbenchSettings)
    {
        $this->command=$command;
        $this->workbenchSettings=$workbenchSettings;
    }

    public function transformReadmeMd($readmepathsource,$readmepathdestination) {

        if(!File::exists($readmepathsource)) {
            $this->command->error('File '.$readmepathsource.' not exist');
            exit();
        }

        $dir = \Padosoft\Workbench\Parameters\Dir::adjustPath(__DIR__).'resources/index.html';
        if(!File::exists($dir)) {
            $this->command->error('File '.$dir.' not exist');
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
        $list = array_diff(File::directories($destination),array($destination.'\resources'));
        $list = array_diff($list,array($destination.'/resources'));
        $documentation_mod = $documentation_mod."<ul>";
        foreach ($list as $tag) {
            $tag = File::basename(\Padosoft\Workbench\Parameters\Dir::adjustPath($tag));
            $documentation_mod = $documentation_mod."<li><a href = 'https://".$this->workbenchSettings->requested['organization']['valore'].".github.io/".$this->workbenchSettings->requested['packagename']['valore']."/".$tag."'>".$tag."</a></li>";
        }
        $documentation_mod = $documentation_mod."</ul>";
        $index = str_replace($documentation, $documentation_mod,$index);

        file_put_contents($readmepathdestination, $index);



    }


}