<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 30/03/2016
 * Time: 12:18
 */

namespace Padosoft\Workbench\Test;


use Padosoft\Workbench\Workbench;
use Illuminate\Support\Facades\Artisan;

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
        $action = "cre";
        $domain = "prova";
        Artisan::call('workbench:new',['action'=>$action,'domain'=>$domain]);
    }
}
