<?php
return [
    'action' => env(
        'WORKBENCH_ACTION',
        'create'
    ),
    'type' => env(
        'WORKBENCH_TYPE',
        'laravel'
    ),
    'dir' => env(
        'WORKBENCH_DIR',
        ''
    ),
    'attemps' => env( 'WORKBENCH_ATTEMPS',
        '5'
    ),
    'git' => [
        'hosting' => env('WORKBENCH_GIT_HOSTING', 'github'),
        'action' => env('WORKBENCH_GIT_ACTION', 'pull'),
        'github' => [
            'user' => env('WORKBENCH_GIT_GITHUB_USER', ''),
            'password' => env('WORKBENCH_GIT_GITHUB_PASSWORD', ''),
            'email' => env('WORKBENCH_GIT_GITHUB_EMAIL', ''),
            'organization' => env('WORKBENCH_GIT_GITHUB_ORGANIZATION', 'padosoft'),
        ],
        'bitbucket' => [
            'user' => env('WORKBENCH_GIT_BITBUCKET_USER', ''),
            'password' => env('WORKBENCH_GIT_BITBUCKET_PASSWORD', ''),
            'email' => env('WORKBENCH_GIT_BITBUCKET_EMAIL', ''),
            'organization' => env('WORKBENCH_GIT_BITBUCKET_ORGANIZATION', 'padosoft'),
        ],
    ],
    'ssh' => [
        'server' => env('WORKBENCH_SSH_SERVER', ''),
        'user' => env('WORKBENCH_SSH_USER', ''),
        'password' => env('WORKBENCH_SSH_SERVER', ''),
    ],
    'type_repository' => [
        'laravel' => env('WORKBENCH_TYPE_REPOSITORY_LARAVEL', 'laravel5.2.x-skeleton'),
        'normal' => env('WORKBENCH_TYPE_REPOSITORY_NORMAL', ''),
        'laravel_package' => env('WORKBENCH_TYPE_REPOSITORY_LARAVEL_PACKAGE', 'laravel5.2.x-package-skeleton'),
        'agnostic_package' => env('WORKBENCH_TYPE_REPOSITORY_AGNOSTIC_PACKAGE', 'package-skeleton'),
    ],
    'substitute' => [
        'author' =>env('WORKBENCH_SUBSTITUTION_AUTHOR', 'Padosoft'),
        'emailauthor' =>env('WORKBENCH_SUBSTITUTION_EMAILAUTHOR', 'helpdesk@padosoft.com'),
        'siteauthor' =>env('WORKBENCH_SUBSTITUTION_SITEAUTHOR', 'www.padosoft.com'),
        'vendor' =>env('WORKBENCH_SUBSTITUTION_VENDOR', 'Padosoft'),
        'files' =>env('WORKBENCH_SUBSTITUTION_FILES', 'readme.md,changelog.md,license.md,travis.yml,composer.json,src/config/sedCommand.sh,src/config/sedCommandProvider.sh'),
    ]
    
    
 ];
