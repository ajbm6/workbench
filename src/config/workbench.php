<?php
return [
    'type' => env(
        'WORKBENCH_TYPE',
        'laravel'
    ),
    'dir' => env(
        'WORKBENCH_DIR',
        ''
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
 ];
