<?php

namespace Padosoft\Workbench;

use Illuminate\Console\Command;
use Config;


class workbench extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workbench:new
                            {action? : }
                            {domain? : }
                            {--t|type= : }
                            {--d|dir= : }
                            {--g|git= : }
                            {--a|gitaction= : }
                            {--u|user= : }
                            {--p|password= : }
                            {--e|email= : }
                            {--o|organization= : }
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

