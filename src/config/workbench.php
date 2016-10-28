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
    'dir'  => env(
        'WORKBENCH_DIR',
        'public'
    ),
    'diraccess' => [
        'private' => [
            'apache' => env('WORKBENCH_DIR_PRIVATE_APACHE','/var/www/html/private/'),
            'local' => env('WORKBENCH_DIR_PRIVATE_LOCAL','Y:/private/'),
            'packages' => env('WORKBENCH_DIR_PRIVATE_PACKAGES','Y:/private/laravel-packages/www/packages/'),
            'doc' => env('WORKBENCH_DIR_PRIVATE_DOC','Y:/private/laravel-packages/www/doc/'),
        ],
        'public' => [
            'apache' => env('WORKBENCH_DIR_PUBLIC_APACHE','/var/www/html/public/'),
            'local' => env('WORKBENCH_DIR_PUBLIC_LOCAL','Y:/public/'),
            'packages' => env('WORKBENCH_DIR_PUBLIC_PACKAGES','Y:/public/laravel-packages/www/packages/'),
            'doc' => env('WORKBENCH_DIR_PUBLIC_DOC','Y:/Public/laravel-packages/www/doc/'),
        ],
    ],
    'dirtype' => env(
        'WORKBENCH_DIRTYPE',
        'public'
    ),
    'attemps' => env( 'WORKBENCH_ATTEMPS',
        '5'
    ),
    'git' => [
        'hosting' => env('WORKBENCH_GIT_HOSTING', 'github'),
        'action' => env('WORKBENCH_GIT_ACTION', 'push'),
        'user' => env('WORKBENCH_GIT_USER', ''),
        'password' => env('WORKBENCH_GIT_PASSWORD', ''),
        'email' => env('WORKBENCH_GIT_EMAIL', ''),

    ],
    'githookenable' => env( 'WORKBENCH_GITHOOKENABLE',
        false
    ),
    'organization' => env('WORKBENCH_GIT_GITHUB_ORGANIZATION', 'padosoft'),
    'ssh' => [
        'server' => env('WORKBENCH_SSH_SERVER', '192.168.0.29'),
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
        'files' =>env('WORKBENCH_SUBSTITUTION_FILES', 'readme.md,changelog.md,license.md,.travis.yml,composer.json,tests/config/sedCommand.sh,tests/config/sedCommandProvider.sh,sami_config.php'),
    ],
    'php_path' => env( 'WORKBENCH_PHP_PATH',
        'C:/xampp/php/php.exe'
    ),
    'common_dev_lib_path' => env( 'WORKBENCH_PHP_PATH','Y:/Public/common-dev-lib'),
    'github_token' => env( 'WORKBENCH_GITHUB_TOKEN',''),
    'scrutinizer_token' => env( 'WORKBENCH_SCRUTINIZER_TOKEN',''),
];
