<?php
namespace TencentCloud\Cls;

use Cls\LogGroup;
use Cls\Log\Content;
use Cls\Log;
use Cls\LogGroupList;
use TencentCloud\Cls\Models\Request\PutLogsRequest;
use TencentCloud\Cls\Models\Response\PutLogsResponse;

date_default_timezone_set ( 'Asia/Shanghai' );

if(!defined('API_VERSION'))
    define('API_VERSION', '0.0.1');
if(!defined('USER_AGENT'))
    define('USER_AGENT', 'log-php-sdk-v-0.0.1');

/**
 * Client class is the main class in the SDK. It can be used to
 * communicate with LOG server to put/get data.
 *
 * @author log_dev
 */
class Client {

    /**
     * @var string TencentCloud accessKey
     */
    protected $accessKeySecret;
    
    /**
     * @var string TencentCloud accessKeyId
     */
    protected $accessKeyId;

    /**
     *@var string TencentCloud sts token
     */
    protected $accessToken;

    /**
     * @var string LOG endpoint
     */
    protected $endpoint;

    /**
     * @var string Check if the host if row ip.
     */
    protected $isRowIp;

    /**
     * @var integer Http send port. The dafault value is 80.
     */
    protected $port;

    /**
     * @var string log sever host.
     */
    protected $logHost;

    /**
     * @var string the local machine ip address.
     */
    protected $source;

    /**
     * TencentCloud_Log_Client constructor
     *
     * @param string $endpoint
     *            LOG host name, for example
     * @param string $accessKeyId
     *            TencentCloud accessKeyId
     * @param string $accessKeySecret
     * @param string $token
     */
    public function __construct($endpoint, $accessKeyId, $accessKeySecret, $token = "") {
        $this->setEndpoint ( $endpoint );
        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
        $this->accessToken = $token;
        $this->source = Util::getLocalIp();
    }

    /**
     * @param $endpoint
     */
    private function setEndpoint($endpoint) {
        $pos = strpos ( $endpoint, "://" );
        if ($pos !== false) {
            $pos += 3;
            $endpoint = substr ( $endpoint, $pos );
        }

        $pos = strpos ( $endpoint, "/" );
        if ($pos !== false) {
            $endpoint = substr ( $endpoint, 0, $pos );
        }

        $this->port = 80;
        $pos = strpos ( $endpoint, ':' );
        if ($pos !== false) {
            $this->port = ( int ) substr ( $endpoint, $pos + 1 );
            $endpoint = substr ( $endpoint, 0, $pos );
        }

        $this->isRowIp = Util::isIp ( $endpoint );
        $this->logHost = $endpoint;
        $this->endpoint = $endpoint . ':' . $this->port;
    }
     
    /**
     * GMT format time string.
     * 
     * @return string
     */
    protected function getGMT()
    {
        return gmdate ( 'D, d M Y H:i:s' ) . ' GMT';
    }


    /**
     * Decodes a JSON string to a JSON Object.
     * Unsuccessful decode will cause an TencentCloud_Log_Exception.
     *
     * @param $resBody
     * @param $requestId
     * @return array|null
     * @throws TencentCloudLogException
     */
    protected function parseToJson($resBody, $requestId)
    {
        if (! $resBody) { return NULL;}
        $result = json_decode ( $resBody, true );
        if ($result === NULL){
          throw new TencentCloudLogException ( 'BadResponse', "Bad format,not json: $resBody", $requestId );
        }
        return $result;
    }

    /**
     * @param $method
     * @param $url
     * @param $body
     * @param $headers
     * @return array
     * @throws RequestCoreException
     */
    protected function getHttpResponse($method, $url, $body, $headers)
    {
        $request = new RequestCore ( $url );
        foreach ( $headers as $key => $value ) {
            $request->add_header ( $key, $value );
        }
        $request->set_method ( $method );
        $request->set_useragent(USER_AGENT);
        if ($method == "POST" || $method == "PUT") {
            $request->set_body ( $body );
        }

        $request->send_request ();
        $response = array ();
        $response [] = ( int ) $request->get_response_code ();
        $response [] = $request->get_response_header ();
        $response [] = $request->get_response_body ();
        return $response;
    }

    /**
     * @param $url
     * @param $body
     * @param $headers
     * @return array
     * @throws TencentCloudLogException
     */
    private function sendRequest($url, $body, $headers)
    {
        try {
            list ( $responseCode, $header, $resBody ) =
                    $this->getHttpResponse ( "POST", $url, $body, $headers );
        } catch ( \Exception $ex ) {
            throw new TencentCloudLogException ( $ex->getMessage (), $ex->__toString () );
        }

        $requestId = isset($header ['x-cls-requestid']) ? $header ['x-cls-requestid'] : '';
        if ($responseCode == 200) {
          return array ($resBody, $header);
        }

        $exJson = $this->parseToJson ( $resBody, $requestId );
        if (isset($exJson ['errorcode']) && isset($exJson ['errormessage'])) {
            throw new TencentCloudLogException ( $exJson ['errorcode'],
                        $exJson ['errormessage'], $requestId );
        } else {
            if ($exJson) {
                $exJson = ' The return json is ' . json_encode($exJson);
            } else {
                $exJson = '';
            }
            throw new TencentCloudLogException ( 'RequestError',
                        "Request is failed. Http code is $responseCode.$exJson", $requestId );
        }
    }

    /**
     * @param $body
     * @param $resource
     * @param $params
     * @param $headers
     * @return array
     * @throws TencentCloudLogException
     */
    private function send($body, $resource, $params, $headers)
    {
        $headers ['Content-Type'] = 'application/x-protobuf';
        $headers ['User-Agent'] = API_VERSION;
        $headers ['Content-Length'] = 0;
        $headers ['Host'] = $this->logHost;
        $headers ['Date'] = $this->GetGMT ();
        if ($body) {
            $headers ['Content-Length'] = strlen ( $body );
        }
        if(strlen($this->accessToken) >0) {
            $headers ['X-Cls-Token'] = $this -> accessToken;
        }

        $signature = Util::getRequestAuthorization($this->accessKeyId, $this->accessKeySecret,
            "POST", $resource, $params, $headers);
        $headers ['Authorization'] = $signature;
        
        $url = $resource;
        if ($params) {
            $url .= '?' . Util::urlEncode ( $params );
        }
        $url = "http://$this->endpoint$url";
        return $this->sendRequest ($url, $body, $headers);
    }

    /**
     * Put logs to CLS Service.
     * Unsuccessful operation will cause an TencentCloudLogException.
     *
     * @param PutLogsRequest $request the PutLogs request parameters class
     * @return PutLogsResponse
     * @throws TencentCloudLogException
     */
    public function putLogs(PutLogsRequest $request)
    {
        if (count ( $request->getLogitems () ) > 4096)
            throw new TencentCloudLogException ( 'InvalidLogSize',
                "logItems' length exceeds maximum limitation: 4096 lines." );

        $logGroupList = new LogGroupList();
        $logGroup = new LogGroup();
        $logItems = $request->getLogItems();
        $cls_logs = array();
        foreach ( $logItems as $logItem ) {
            $log = new Log ();
            $log->setTime ( $logItem->getTime () );
            $content = $logItem->getContents ();
            $cls_contents = array();
            foreach ( $content as $key => $value ) {
                $content = new Content ();
                $content->setKey ( $key );
                $content->setValue ( $value );
                array_push($cls_contents, $content);
            }
            $log->setContents ($cls_contents);
            array_push($cls_logs, $log);
        }
        $logGroup->setLogs ($cls_logs);
        $logGroupList ->setLogGroupList([$logGroup]);
        $body = $logGroupList->serializeToString();

        unset ( $logGroup );
        unset ( $logGroupList );

        $bodySize = strlen ( $body );
        if ($bodySize > 3 * 1024 * 1024) {
            throw new TencentCloudLogException ( 'InvalidLogSize', "logItems' size exceeds maximum limitation: 5 MB." );
        }

        $params = array ();
        $headers = array ();
        $headers ['Content-Type'] = 'application/x-protobuf';

        $params["topic_id"] = $request->getTopicId()!== null ? $request->getTopicId() : '';
        $resource = "/structuredlog";
        list ($resBody, $header ) = $this->send ($body, $resource, $params, $headers);
        return new PutLogsResponse ( $header );
    }
}



