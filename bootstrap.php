<?php

require '../vendor/autoload.php';
// DI is necessary to Service Class
$di = new \Phalcon\DI\FactoryDefault();

//now register the extensions globally
$di->set('apibird', function() {
    $api = new \ApiBird\ServiceProvider();
    $api->registerExtensions([
        'json' => '\\ApiBird\\Extension\\Json',
        'xml' => '\\ApiBird\\Extension\\Xml',
        'form' => '\\ApiBird\\Extension\\Form',
        'html' => '\\ApiBird\\Extension\\Html',
        'text' => '\\ApiBird\\Extension\\Text',
        'csv' => '\\ApiBird\\Extension\\Csv',
        'multipart' => '\\ApiBird\\Extension\\Multipart',
    ]);
    $api->setErrorDataHandler(function($data, $code, $msg) {
        return \ApiBird\JSend::error($code . ' - ' . $msg, $data);
    });
    $api->setDefaultProduces('json')->setDefaultConsumes('form')->enableCors();
    return $api;
}, true);
// this enables serverCache method
$di->set('cache', function() {
    $frontCache = new Phalcon\Cache\Frontend\Data([
        "lifetime" => 3600
    ]);
    $cache = new Phalcon\Cache\Backend\Apc($frontCache, [
        'prefix' => 'datacache'
    ]);
    return $cache;
}, true);

// create api bird
$app = new \ApiBird\Micro($di);
