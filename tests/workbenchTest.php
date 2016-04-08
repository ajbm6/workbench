<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 30/03/2016
 * Time: 12:18
 */

namespace Padosoft\Workbench\Test;


use Illuminate\Console\Command;
use Illuminate\Foundation\Console\IlluminateCaster;
use Mockery\Mock;
use Padosoft\Workbench\Workbench;
use Illuminate\Support\Facades\Artisan;
use Mockery;

class WorkbenchTest extends \Padosoft\LaravelTest\TestBase
{

    protected $workbench;

    public function setUp()
    {
        //$this->workbench = new Workbench();
        parent::setUp();
    }

    /** @test */
    public function testHardWorkCreateOk()
    {
        $action = "create";
        $domain = "prova";
        //Artisan::call('workbench:new',['action'=>$action,'domain'=>$domain]);
    }

    /** @test */
    public function testHardWorkCreateNoOk()
    {
        $action = "crea";
        $domain = "prova";
        $type = "laravel";
        $dir = "y:/public/";
        $git = "y:/public/";
        $gitaction = "push";
        $user="alevento";
        $password="asd";


        //$cmd=Mockery::mock('Padosoft\Workbench\Workbench');
        //$cmd->shouldReceive('ask')->with('Ale');
        Artisan::call('workbench:new',[
            //'action'=>$action,
            //'domain'=>$domain,
            //'--type'=>$type,
            //'--dir'=>$dir,
            //'--git'=>$git,
            //'--gitaction'=>$gitaction,
            //'--user'=>$user,
            //'--password'=>$password,
            '--silent'=>true
        ]);

    }


}
