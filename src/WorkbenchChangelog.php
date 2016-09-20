<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 16/09/2016
 * Time: 17:15
 */

namespace Padosoft\Workbench;



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
                $this->changes['added'][]=$choice;
                $this->question();
                break;
            case 'Changed for changes in existing functionality.':
                $this->changes['changed'][]=$choice;
                $this->question();
                break;
            case 'Deprecated for once-stable features removed in upcoming releases.':
                $this->changes['deprecated'][]=$choice;
                $this->question();
                break;
            case 'Removed for deprecated features removed in this release.':
                $this->changes['removed'][]=$choice;
                $this->question();
                break;
            case 'Fixed for any bug fixes.':
                $this->changes['fixed'][]=$choice;
                $this->question();
                break;
            case 'Security to invite users to upgrade in case of vulnerabilities.':
                $this->changes['security'][]=$choice;
                $this->question();
                break;
            case 'Exit.':
                break;
        }

    }

}