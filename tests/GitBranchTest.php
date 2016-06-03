<?php

/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 02/06/2016
 * Time: 13:09
 */

//namespace Padosoft\Workbench\Test;

use GitWrapper\GitWrapper;
use League\CommonMark\CommonMarkConverter;
use Illuminate\Support\Facades\File;

class GitBranchTest extends \Padosoft\LaravelTest\TestBase
{

    public function setUp()
    {
        //$this->workbench = new Workbench();
        parent::setUp();
    }


    /** @test */
    public function testBranch()
    {

        $source = 'Y:/Public/laravel-packages/www/packages/b2msrl/package27052016_44';
        $destination = 'Y:/Public/laravel-packages/www/doc/b2msrl/package27052016_44';

        /*$gitWrapper = new GitWrapper();
        $gitWorkingCopy=$gitWrapper->init($destination,[]);
        $gitWrapper->git("config --global user.name alevento");
        $gitWrapper->git("config --global user.email alessandro.manneschi@gmail.com");
        $gitWrapper->git("config --global user.password 129895ale");
        $gitWorkingCopy->addRemote('origin',"https://alevento:129895ale@github.com/b2msrl/package27052016_44.git" );
        $gitWorkingCopy->checkoutNewBranch('gh-pages');
        $gitWorkingCopy->add('.');
        $gitWorkingCopy->commit('My commit message');
        $gitWorkingCopy->push('origin','gh-pages');*/

        $readmepathsource = \Padosoft\Workbench\Parameters\Dir::adjustPath($source).'readme.md';
        $readmepathdestination = \Padosoft\Workbench\Parameters\Dir::adjustPath($destination).'index.html';

        if(!File::exists($readmepathsource)) {
            return ;
        }


        if(!File::exists($readmepathsource)) {
            $this->error('File '.$readmepathsource.' not exist');
            exit();
        }
        $dir = 'Y:/Public/laravel-packages/www/packages/padosoft/workbench/src/resources/index.html';
        if(!File::exists($dir)) {
            $this->error('File '.$dir.' not exist');
            exit();
        }
        File::copy($dir,$readmepathdestination);
        $index = file_get_contents($readmepathdestination);
        str_replace('@@@package_name', $this->requested['packagename']['valore'],$index);
        $readme=file_get_contents($readmepathsource);
        $converter = new CommonMarkConverter();
        $index_mod = str_replace("@@@readme", $converter->convertToHtml($readme),$index);
        file_put_contents($readmepathdestination, $index_mod);
        
    }
}
