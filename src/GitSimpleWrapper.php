<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 07/10/2016
 * Time: 11:26
 */

namespace Padosoft\Workbench;

use Symfony\Component\Process\ExecutableFinder;

class GitSimpleWrapper
{

    private $gitBinary;
    private $workingDirectory;

    public function __construct($workingDirectory = null , $gitBinary = null)
    {
        $this->workingDirectory =$workingDirectory;
        if (null === $workingDirectory) {
            $this->workingDirectory = __DIR__;
        }

        if (null === $gitBinary) {
            // @codeCoverageIgnoreStart
            $finder = new ExecutableFinder();
            $this->gitBinary = '"'.str_replace("\\","/",$finder->find('git')).'"';
            if (!$this->gitBinary) {
                throw new GitSimpleException('Unable to find the Git executable.');
            }
        }


    }

    public function git($command)
    {
        exec($this->gitBinary." -C ".$this->workingDirectory." $command 2>&1", $output, $returned_val);

        if ($returned_val > 0){
            throw new GitSimpleException(explode("\r\n",$output));
        }else{
            return $output;
        }


    }

    /**
     * Sets the path to the Git binary.
     *
     * @param string $gitBinary
     *   Path to the Git binary.
     *
     * @return \GitWrapper\GitWrapper
     */
    public function setGitBinary($gitBinary)
    {
        $this->gitBinary = $gitBinary;
        return $this;
    }


    /**
     * Returns the path to the Git binary.
     *
     * @return string
     */
    public function getGitBinary()
    {
        return $this->gitBinary;
    }


    /**
     * Sets the path to the Git binary.
     *
     * @param string $gitBinary
     *   Path to the Git binary.
     *
     * @return \GitWrapper\GitWrapper
     */
    public function setWorkingDirectory($workingDirectory)
    {
        $this->workingDirectory = $workingDirectory;
        return $this;
    }


    /**
     * Returns the path to the Git binary.
     *
     * @return string
     */
    public function getWorkingDirectory()
    {
        return $this->workingDirectory;
    }

}