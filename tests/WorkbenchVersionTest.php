<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 30/03/2016
 * Time: 12:18
 */

namespace Padosoft\Workbench\Test;


use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\ExecutableFinder;
use Config;
use Padosoft\HTTPClient\HttpHelperFacade;

class WorkbenchVersionTest extends \Padosoft\LaravelTest\TestBase
{

    protected $workbench;

    public function setUp()
    {
        //$this->workbench = new Workbench();
        parent::setUp();
    }


    /** @test */
    public function testHardWorkCreateNoOk()
    {






        //$finder = new ExecutableFinder();
        //$finder->addSuffix('phar');

        /*$this->gitBinary = '"'.str_replace("\\","/",$finder->find('git',null,[Config::get('workbench.git_path')])).'"';
        if (!$this->gitBinary) {
            throw new GitException('Unable to find the Git executable.');
        }
        $this->pharSamiBinary = '"'.str_replace("\\","/",$finder->find('sami',null,[Config::get('workbench.common_dev_lib_path')])).'"';
        if (!$this->pharSamiBinary) {
            throw new Exception('Unable to find the Sami phar.');
        }
        echo $this->pharSamiBinary;*/




        $dir="Y:/Public/laravel-packages/www/laravel/5.2.x/packages/Padosoft/workbench/";
        $user="alevento";
        $password=env('PWD_ALE_GITHUB');
        $email="alessandro.manneschi@gmail.com";

        Artisan::call('workbench:version',[
            'dir'=>$dir,
            '--user'=>$user,
            '--password'=>$password,
            '--email'=>$email,
        ]);

    }


}
