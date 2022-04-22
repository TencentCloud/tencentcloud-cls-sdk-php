<?php

namespace TencentCloud\Cls\Models\Response;
/**
 * The base response class of all log response.
 *
 * @author farmerx
 */
class Response {

    /**
     * @var array HTTP response header
     */
    private $headers;

    /**
     * TencentCloud_Log_Models_Response constructor
     *
     * @param $headers
     */
    public function __construct($headers) {
        $this->headers = $headers;
    }
    
    /**
     * Get all http headers
     *
     * @return array HTTP response header
     */
    public function getAllHeaders()
    {
        return $this->headers;
    }
    
    /**
     * Get specified http header
     *
     * @param string $key
     *            key to get header
     *
     * @return string HTTP response header. '' will be return if not set.
     */
    public function getHeader($key)
    {
        return isset($this->headers [$key]) ? $this->headers [$key] : '';
    }
    
    /**
     * Get the request id of the response. '' will be return if not set.
     *
     * @return string request id
     */
    public function getRequestId()
    {
        return isset($this->headers ['x-cls-requestid']) ? $this->headers ['x-cls-requestid'] : '';
    }
}
