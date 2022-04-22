<?php

require_once __DIR__.'../../vendor/autoload.php';

use TencentCloud\Cls\Models\Request\PutLogsRequest;
use TencentCloud\Cls\Models\LogItem;
use TencentCloud\Cls\Client;
use TencentCloud\Cls\TencentCloudLogException;



function putLogs($client, $topicId) {
    $contents = array(
        'TestKey'=>'TestContent',
        'test2'=>'beijing'
    );
    $logItem = new LogItem();
    $logItem->setTime(time());
    $logItem->setContents($contents);
    $logItems = array($logItem);
    $request = new PutLogsRequest($topicId, null, $logItems);

    try {
        $response = $client->putLogs($request);
        var_dump($response->getRequestId());
    } catch (TencentCloudLogException $ex) {
        var_dump($ex);
    } catch (Exception $ex) {
        var_dump($ex);
    }
}

$endpoint = 'ap-guangzhou.cls.tencentcs.com';
$accessKeyId = '';
$accessKey = '';
$topicId = '';
$token = "";


$client = new Client($endpoint, $accessKeyId, $accessKey,$token);
putLogs($client, $topicId);

