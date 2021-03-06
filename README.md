# workbench


[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![SensioLabsInsight][ico-sensiolab]][link-sensiolab]

CONSOLE FOR NEW PROJECT:
![screenshoot](https://raw.githubusercontent.com/padosoft/workbench/master/resources/img/wnc.gif)

Table of Contents
=================

  * [workbench](#workbench)
  * [Table of Contents](#table-of-contents)
  * [Prerequisites](#prerequisites)
  * [Install](#install)
  * [Usage](#usage)
  * [Example](#example)
  * [Screenshots](#screenshots)
  * [Change Log](#change-log)
  * [Testing](#testing)
  * [Contributing](#contributing)
  * [Security](#security)
  * [API Documentation](#api-documentation)
  * [Credits](#credits)
  * [About Padosoft](#about-padosoft)
  * [License](#license)

# Prerequisites

# Install

This package can be installed through Composer.

``` bash
composer require padosoft/workbench
```
You must install this service provider.

``` php
// config/app.php
'provider' => [
    ...
    Padosoft\Workbench\WorkbenchServiceProvider::class,
    ...
];
```

You can publish the config file of this package with this command:
``` bash
php artisan vendor:publish --provider="Padosoft\Workbench\WorkbenchServiceProvider"
```
The following config file will be published in `config/workbench.php`
``` php
[

]
```

Sometimes in case of problem you can use:
``` bash
php artisan config:clear
```


# Usage

php artisan workbench:new

l'option --help mostra i parametri

php artisan workbench:new --help
Usage:
  workbench:new [options] [--] [<action>] [<domain>]

Arguments:

  action                                   create or delete
  
  domain                                   domain name
  
  
Options:

  -t, --type[=TYPE]                        laravel, normal, laravel_package or agnostic_package
  
  -d, --dirtype[=DIRTYPE]                  project dir type, public or private, path set in config
  
  -g, --git[=GIT]                          github or bitbucket
  
  -u, --user[=USER]                        git user
  
  -p, --password[=PASSWORD]                git password
  
  -e, --email[=EMAIL]                      git email
  
  -o, --organization[=ORGANIZATION]        organization in github or bitbucket
  
  -s, --silent                             no questions
  
      --sshhost[=SSHHOST]                  host ssh
      
      --sshuser[=SSHUSER]                  user ssh
      
      --sshpassword[=SSHPASSWORD]          password ssh
      
      --filehosts                          add or remove in local file /etc/hosts
      
      --packagename[=PACKAGENAME]          name of package
      
      --packagedescr[=PACKAGEDESCR]        description of package
      
      --packagekeywords[=PACKAGEKEYWORDS]  keywords of package
      
  -h, --help                               Display this help message
  
  -q, --quiet                              Do not output any message
  
  -V, --version                            Display this application version
  
      --ansi                               Force ANSI output
      
      --no-ansi                            Disable ANSI output
      
  -n, --no-interaction                     Do not ask any interactive question
  
      --env[=ENV]                          The environment the command should run under.
      
  -v|vv|vvv, --verbose                     Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug


Help:

 The workbench:new ....




In the case of the package the part of virtualhost is not required, if inserted in the option is ignored

Example with parameters

Laravel create:

php artisan workbench:new create laravelsite -t laravel -d public -g github -u alevento -p******** -e ale@mail.it -o b2msrl --sshhost=192.168.0.29 --sshuser=root --sshpassword=********* --packagename=laravelsite --packagedescr=descr --packagekeywords=descr


Laravel package create:

Y:\Public\laravel-packages\www\laravel\5.2.x>php artisan workbench:new create laravelpackage -t laravel_package -d public -g github -u user -p********** -e ale@mail.it -o b2msrl --packagename=laravelpackage --packagedescr=descr --packagekeywords=descr


Agnostic package create:

Y:\Public\laravel-packages\www\laravel\5.2.x>php artisan workbench:new create agnosticpackage -t agnostic_package -d public -g github -u user -p******** -e ale@mail.it -o b2msrl --packagename=agnosticpackage --packagedescr=descr --packagekeywords=descr


Silent create:

Y:\Public\laravel-packages\www\laravel\5.2.x>php artisan workbench:new create laravelsilent --silent


In silent mode 
In silent mode, the parameters must be filled in workbench.php


Parameters in workbench.php config file:


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
        
        'files' =>env('WORKBENCH_SUBSTITUTION_FILES', 'readme.md,changelog.md,license.md,travis.yml,composer.json,tests/config/sedCommand.sh,tests/config/sedCommandProvider.sh'),
    ],
];


## Example

## SCREENSHOOTS

CONSOLE FOR NEW PROJECT:

![screenshoot](https://raw.githubusercontent.com/padosoft/workbench/master/resources/img/wnc.gif)

CONSOLE FOR NEW PACKAGE:

![screenshoot](https://raw.githubusercontent.com/padosoft/workbench/master/resources/img/wnpc.gif)

CONSOLE FOR VERSIONING OF PACKAGE:

![screenshoot](https://raw.githubusercontent.com/padosoft/workbench/master/resources/img/wvpc.gif)


# Change Log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

# Testing

# Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

# Security

If you discover any security related issues, please email  instead of using the issue tracker.

# API Documentation

Please see API documentation at [http://padosoft.github.io/workbench](http://padosoft.github.io/workbench)

- [master](http://padosoft.github.io/workbench/build/master/)

# Credits

- [Padosoft](https://github.com/padosoft)

- [All Contributors](../../contributors)

# About Padosoft

Padosoft is a software house based in Florence, Italy. Specialized in E-commerce and web sites.

# License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


[ico-version]: https://img.shields.io/packagist/v/padosoft/workbench.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/padosoft/workbench/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/padosoft/workbench.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/padosoft/workbench.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/padosoft/workbench.svg?style=flat-square
[ico-sensiolab]: https://insight.sensiolabs.com/projects/@@@sensiolab/small.png

[link-packagist]: https://packagist.org/packages/padosoft/workbench
[link-travis]: https://travis-ci.org/padosoft/workbench
[link-scrutinizer]: https://scrutinizer-ci.com/g/padosoft/workbench/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/padosoft/workbench
[link-downloads]: https://packagist.org/packages/padosoft/workbench
[link-sensiolab]: https://insight.sensiolabs.com/projects/@@@sensiolabs
