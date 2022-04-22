<?php

require_once __DIR__.'../../vendor/autoload.php';

use TencentCloud\Cls\Client;
use TencentCloud\Cls\LoggerFactory;


// please update the configuration according your profile
$endpoint = 'ap-guangzhou.cls.tencentcs.com';
$accessKeyId = '';
$accessKey = '';
$topicId = '';
$token = "";


// define MyCallBack function
class MyCallBack extends \TencentCloud\Cls\Callback {
    function onCompletion(\TencentCloud\Cls\Result $result)
    {
        var_dump($result);
    }
}


/**
 * client and logger usage
 */
 // create a log client
$client = new Client($endpoint, $accessKeyId, $accessKey, $token);

try {
    $batchLogger = LoggerFactory::getLogger($client, $topicId, new MyCallBack());
    // batch submit single string message, with default cache size 100
    for($i = 1; $i <= 129; $i++){
        $batchLogger->info('something wrong with the inner info '.$i);
    }
    $batchLogger->logFlush();
} catch (Exception $e) {
    var_dump($e);
}


try {
    $anotherLogger = LoggerFactory::getLogger($client, $topicId, new MyCallBack());

    $anotherLogger->info('test log message 000 info');
    $anotherLogger->warn('test log message 000 warn');
    $anotherLogger->error('test log message 000 error');
    $anotherLogger->debug('test log message 000 debug');

    $logMap['level'] = 'info';
    $anotherLogger->infoArray($logMap);
    $logMap['level'] = 'debug';
    $anotherLogger->debugArray($logMap);
    $logMap['level'] = 'warn';
    $anotherLogger->warnArray($logMap);
    $logMap['level'] = 'error';
    $anotherLogger->errorArray($logMap);

    $anotherLogger->logFlush();
} catch (Exception $e) {
    var_dump($e);
}








