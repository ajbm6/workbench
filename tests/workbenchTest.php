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
use Padosoft\Workbench\HttpHelper;
use Padosoft\Workbench\MethodHttpHelper;
use Padosoft\Workbench\Workbench;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use phpseclib\Net\SSH2;
use Symfony\CS\Fixer\Symfony\PhpdocToCommentFixer;
use Padosoft\Workbench\HeaderHttpHelper;
use GuzzleHttp\Client;

class WorkbenchTest extends \Padosoft\LaravelTest\TestBase
{

    protected $workbench;

    public function setUp()
    {
        //$this->workbench = new Workbench();
        parent::setUp();
    } 

    /** @test */
    /*public function testHardWorkCreateOk()
    {
        $action = "create";
        $domain = "prova";
        //Artisan::call('workbench:new',['action'=>$action,'domain'=>$domain]);

        $ssh = new SSH2('192.168.0.29');
        if (!$ssh->login('root', 'padosoft2015')) {
            exit('Login Failed');
        }
        $ssh->exec('mkdir /var/www/html/prova/');
    }*/

    /** @test */
    public function testHardWorkCreateNoOk()
    {
        $action = "create";
        $domain = "prova2";
        $type = "laravel";
        $dir = "y:/public/";
        $git = "github";
        $gitaction = "push";
        $user="alevento";
        $password="asd";
        $email="a@a.it";
        $organization="b2m";
        $sshhost='192.168.0.29';
        $sshuser='ale';
        $sshpassword='ale';

        $head = new HeaderHttpHelper();
        $head->headers__authorization__username='alevento';
        $head->headers__authorization__password='129895ale';
        $head->json=['name'=>'cicciu'];
        //$head->authorization=['alevento','129895ale'];
        //$head->name="ciccio";
        $client = new client;
        $req = new HttpHelper($client);
        $req->request(MethodHttpHelper::POST,'https://api.github.com/orgs/b2msrl/repos',$head);

        //$cmd=Mockery::mock('Padosoft\Workbench\Workbench');
        //$cmd->shouldReceive('ask')->with('Ale');
        Artisan::call('workbench:new',[
            'action'=>$action,
            'domain'=>$domain,
            '--type'=>$type,
            '--dir'=>$dir,
            '--git'=>$git,
            '--gitaction'=>$gitaction,
            '--user'=>$user,
            '--password'=>$password,
            '--silent'=>false,
            '--organization'=>$organization,
            '--email'=>$email,
            '--sshhost'=>$sshhost,
            '--sshuser'=>$sshuser,
            '--sshpassword'=>$sshpassword

        ]);

    }


}
