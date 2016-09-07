<?php

use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->in($dir = 'Y:/Public/laravel-packages/www/laravel/5.2.x/packages/Padosoft/workbench/src')
;

$versions = GitVersionCollection::create($dir)
    ->addFromTags('0.0.*')
    ->addFromTags('1.0.*')
    ->addFromTags('master')
    ->add('0.0', '0.0 branch')
    ->add('1.0', '1.0 branch')
    ->add('master', 'master branch')
;

return new Sami($iterator,array(
    'theme'                => 'default',
    'title'                => 'Workbench API',
    'versions'             => $versions,
    'build_dir'            => 'Y:/build/%version%',
    'cache_dir'            => 'Y:/cache/%version%',
    'default_opened_level' => 2,
));

