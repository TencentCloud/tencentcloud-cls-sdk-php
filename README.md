# TencentCloud Php Log Service PHP SDK

## SDK RELEASE TIME

2022-04-20

## Introduction

Log Service SDK for PHP，used to set log data to TencentCloud CLS Log Service.

API Reference: 

### Summary

1. Request-Request style Restful API interface
2. Use Protocol buffer to send data 
3. Data can be protobuf compressed when sending to server
4. TencentCloudLogException will be thrown if any error happen
5. Introduce simple logger for submit log easily with different levels
6. Create local log cache to submit several logs in single http post.

## Environment Requirement

1. PHP 5.6.0 and later：Master Branch

## LZ4 压缩上传
1、暂不支持LZ4 压缩上传

## 密钥信息

accessKeyId和accessKey为云API密钥，密钥信息获取请前往[密钥获取](https://console.cloud.tencent.com/cam/capi)。并请确保云API密钥关联的账号具有相应的[SDK上传日志权限](https://cloud.tencent.com/document/product/614/68374#.E4.BD.BF.E7.94.A8-api-.E4.B8.8A.E4.BC.A0.E6.95.B0.E6.8D.AE)

## Demo
```
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

```
