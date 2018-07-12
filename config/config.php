<?php

use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

$dotenv = new Dotenv\Dotenv('./');
$dotenv->load();

$aggregator = new ConfigAggregator([
    \Zend\Validator\ConfigProvider::class,
    \Zend\Log\ConfigProvider::class,
    // Base config provider
    Common\ConfigProvider::class,
    Dynamicus\ConfigProvider::class,
    Audicus\ConfigProvider::class,

    // Load application config in a pre-defined order in such a way that local settings
    // overwrite global settings. (Loaded as first to last):
    //   - `global.php`
    //   - `*.global.php`
    //   - `local.php`
    //   - `*.local.php`
    new PhpFileProvider('config/autoload/{{,*.}global,{,*.}local}.php'),

    // Load development config if it exists
    new PhpFileProvider('config/development.config.php'),
]);

return $aggregator->getMergedConfig();
