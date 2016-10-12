<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 30/08/2016
 * Time: 14:14
 */

namespace Padosoft\Workbench;

use File;

class WorkbenchCopyThread extends \Thread
{
    private $origin;
    private $dest;

    /**
     * WorkbenchCopyThread constructor.
     * @param $origin
     * @param $dest
     */
    public function __construct($origin,$dest){
        $this->origin=$origin;
        $this->dest=$dest;
    }

    /**
     *
     */
    public function run(){
        File::copyDirectory($this->origin,$this->dest);
    }
}