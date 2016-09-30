<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 16/09/2016
 * Time: 17:15
 */

namespace Padosoft\Workbench;

use Illuminate\Console\Command;

class WorkbenchChangelog
{

    private $workbenchSettings;
    private $command;
    private $changes=array();

    public function __construct(WorkbenchSettings $workbenchSettings, Command $command){
        $this->workbenchSettings = $workbenchSettings;
        $this->command = $command;
    }

    /*
     *  Added for new features.
        Changed for changes in existing functionality.
        Deprecated for once-stable features removed in upcoming releases.
        Removed for deprecated features removed in this release.
        Fixed for any bug fixes.
        Security to invite users to upgrade in case of vulnerabilities.
     *
     */
    public function composeChangelog()
    {
        $source = \Padosoft\Workbench\Parameters\Dir::adjustPath($this->workbenchSettings->requested['dir']['valore'].$this->workbenchSettings->requested['domain']['valore'])."CHANGELOG.md";

        $changelog = file_get_contents($source);

        file_put_contents($source, $changelog);


    }

    public function question()
    {
        $choice = $this->command->choice('What do you want to add?',[
            'Added for new features.',
            'Changed for changes in existing functionality.',
            'Deprecated for once-stable features removed in upcoming releases.',
            'Removed for deprecated features removed in this release.',
            'Fixed for any bug fixes.',
            'Security to invite users to upgrade in case of vulnerabilities.',
            'Exit.']);

        switch ($choice)
        {
            case 'Added for new features.':
                $this->changes['added'][]=$this->command->ask('Description of added');
                $this->question();
                break;
            case 'Changed for changes in existing functionality.':
                $this->changes['changed'][]=$this->command->ask('Description of change');
                $this->question();
                break;
            case 'Deprecated for once-stable features removed in upcoming releases.':
                $this->changes['deprecated'][]=$this->command->ask('Description of deprecated');
                $this->question();
                break;
            case 'Removed for deprecated features removed in this release.':
                $this->changes['removed'][]=$this->command->ask('Description of removed');
                $this->question();
                break;
            case 'Fixed for any bug fixes.':
                $this->changes['fixed'][]=$this->command->ask('Description of fixed');
                $this->question();
                break;
            case 'Security to invite users to upgrade in case of vulnerabilities.':
                $this->changes['security'][]=$this->command->ask('Description of security');
                $this->question();
                break;
            case 'Exit.':
                break;
        }
        return $this;
    }

    public function writeChangeLog($fileLog,$tag)
    {
        $file=\Padosoft\Workbench\Parameters\Dir::adjustPath($fileLog);
        $changeLog = file_get_contents($fileLog);

        $toSubstitute= "# Changelog\r\n\r\nAll Notable changes to ".$this->workbenchSettings->getRequested()['packagename']['valore']." will be documented in this file\r\n\r\n";

        $toAddToFile = $toSubstitute."## ".$tag." - ".date("Y-m-d")."\r\n";

        foreach ($this->changes as $key => $values) {
            if(count($this->changes[$key])) {
                $toAddToFile = $toAddToFile."\r\n";
                $toAddToFile = $toAddToFile."### ".ucfirst($key)."\r\n";
            }

            foreach($this->changes[$key] as $change) {
                $toAddToFile = $toAddToFile."- ".$change."\r\n";
            }

        }
        $toAddToFile = $toAddToFile."\r\n";
        $newChangeLog=str_replace($toSubstitute,$toAddToFile, $changeLog);
        file_put_contents($fileLog,$newChangeLog);

    }


    public function getChanges()
    {
        return $this->changes;
    }

}