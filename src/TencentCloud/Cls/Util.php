<?php

namespace TencentCloud\Cls;

class Util {

    /**
     * Get the local machine ip address.
     *
     * @return string
     */
    public static function getLocalIp()
    {
        $local_ip = getHostByName(php_uname('n'));
        if(strlen($local_ip) == 0){
            $local_ip = getHostByName(getHostName());
        }
        return $local_ip;
    }
    
    /**
     * If $ip_str is raw IP address, return true.
     *
     * @return bool
     */
    public static function isIp($ip_str){
        $ip = explode(".", $ip_str);
        for($i=0;$i<count($ip);++$i) {
            if($ip[$i]>255) {
                return 0;
            }
        }
        return preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip_str);
    }

    /**
     * Get url encode.
     *
     * @param $value
     * @return string
     */
    public static function urlEncodeValue($value)
    {
        return urlencode ( $value );
    }

    /**
     * Get url encode.
     *
     * @param $params
     * @return string
     */
    public static function urlEncode($params)
    {
        ksort ( $params );
        $url = "";
        $first = true;
        foreach ( $params as $key => $value ) {
            $val = Util::urlEncodeValue ( $value );
            if ($first) {
                $first = false;
                $url = "$key=$val";
            } else
                $url .= "&$key=$val";
        }
        return $url;
    }

    /**
     * Get request authorization string as defined.
     *
     * @param $secretId
     * @param $secretKey
     * @param string $method
     * @param string $path
     * @param array $params
     * @param array $headers
     * @param int $expire
     * @return string
     */
    public static function getRequestAuthorization($secretId, $secretKey, $method='GET',
                                                   $path='/', $params=array(), $headers=array(),
                                                   $expire=120)
    {
        $filter_headers = array();
        foreach ($headers as $k => $v) {
            $lower_key = strtolower($k);
            if ($lower_key == 'content-type' || $lower_key == 'content-md5' || $lower_key == 'host' || $lower_key[0] == 'x') {
                $filter_headers[$lower_key] = $v;
            }
        }
        $filter_params = array();
        foreach ($params as $k => $v) {
            $filter_params[strtolower($k)] = $v;
        }
        ksort($filter_params);
        ksort($filter_headers);
        $filter_headers = array_map('strtolower', $filter_headers);
        $uri_headers = http_build_query($filter_headers);
        $httpString = strtolower($method) . "\n" . urldecode($path) .
            "\n". http_build_query($filter_params) . "\n" . $uri_headers . "\n";

        $signTime = (string)(time() - 60) . ';' . (string)(time() + $expire);
        $stringToSign = "sha1\n" . $signTime . "\n" . sha1($httpString) . "\n";

        $signKey = hash_hmac('sha1', $signTime, $secretKey);
        $signature = hash_hmac('sha1', $stringToSign, $signKey);

        return "q-sign-algorithm=sha1&q-ak=$secretId" .
            "&q-sign-time=$signTime&q-key-time=$signTime&q-header-list=" .
            join(";", array_keys($filter_headers)) . "&q-url-param-list=" .
            join(";", array_keys($filter_params)) . "&q-signature=$signature";
    }
}

