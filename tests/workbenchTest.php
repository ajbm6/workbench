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
use GitWrapper\GitWrapper;

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
        $domain = "package090620164";
        $type = "laravel";
        $dirtype = "public";
        $git = "github";
        $gitaction = "push";
        $user="alevento";
        $password="129895ale";
        $email="alessandro.manneschi@gmail.com";
        $organization="b2msrl";
        $sshhost='192.168.0.29';
        $sshuser='root';
        $sshpassword='padosoft2015';
        //$packagename='pacchetto 2 Prova';
        $packagedescr='Prova,prova,tre';
        $packagekeywords='prova,workbench,pacchetto';

        /*$head = new HeaderHttpHelper();
        $head->headers__authorization__username='alevento';
        $head->headers__authorization__password='129895ale';
        $head->json=['name'=>'cicciu'];
        $head->headers__content_type = 'application/json';
        //$head->authorization=['alevento','129895ale'];
        //$head->name="ciccio";
        $client = new client;
        $req = new HttpHelper($client);
        $req->request(MethodHttpHelper::POST,'https://api.github.com/orgs/b2msrl/repos',$head);*/

        //https://api.bitbucket.org/2.0/repositories/${team}/${repo}
        //$cmd=Mockery::mock('Padosoft\Workbench\Workbench');
        //$cmd->shouldReceive('ask')->with('Ale');
        Artisan::call('workbench:new',[
            'action'=>$action,
            'domain'=>$domain,
            '--type'=>$type,
            '--dirtype'=>$dirtype,
            '--git'=>$git,
            '--gitaction'=>$gitaction,
            '--user'=>$user,
            '--password'=>$password,
            '--silent'=>false,
            '--organization'=>$organization,
            '--email'=>$email,
            '--sshhost'=>$sshhost,
            '--sshuser'=>$sshuser,
            '--sshpassword'=>$sshpassword,
            //'--packagename'=>$packagename,
            '--packagedescr'=>$packagedescr,
            '--packagekeywords'=>$packagekeywords,
            '--filehosts'

        ]);

    }


}
