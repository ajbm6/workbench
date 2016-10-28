<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 30/03/2016
 * Time: 12:18
 */

namespace Padosoft\Workbench\Test;


use Illuminate\Support\Facades\Artisan;

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
