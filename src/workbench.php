<?php

namespace Padosoft\Workbench;

use Illuminate\Console\Command;
use Config;
use GrahamCampbell\GitHub\Facades\GitHub;

class Workbench extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workbench:new
                            {action? : create or delete}
                            {domain? : domain name}
                            {--t|type= : laravel or normal}
                            {--d|dir= : project dir}
                            {--g|git= : github or bitbucket}
                            {--a|gitaction= : push, pull or force}
                            {--u|user= : git user}
                            {--p|password= : git password}
                            {--e|email= : git email}
                            {--o|organization= : organization in github or bitbucket}
                            {--s|silent : no questions}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = <<<EOF
The <info>workbench:new</info> ....
EOF;



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->hardWork($this->argument(), $this->option());
    }

    /**
     * @param $argument
     * @param $option
     */
    private function hardWork($argument, $option)
    {
        $tuttoOk = true;
        $action = $argument["action"];
        $domain = $argument["domain"];
        $type=$option["type"];
        $dir=$option["dir"];
        $git=$option["git"];
        $gitaction=$option["gitaction"];
        $user=$option["user"];
        $password=$option["password"];
        $email=$option["email"];
        $organization=$option["organization"];
        $silent=$option["silent"];
        

        $this->notifyResult( $tuttoOk);

    }

    /**
     * @param $tuttoOk
     */
    private function notifyResult($tuttoOk)
    {


        if ($tuttoOk) {
            return $this->notifyOK();
        }

        $this->notifyKO();
    }


    private function notifyOK()
    {
        $esito = "";
        $this->line($esito);
    }

    private function notifyKO()
    {
        $esito = "";
        $this->error($esito);
    }





}

