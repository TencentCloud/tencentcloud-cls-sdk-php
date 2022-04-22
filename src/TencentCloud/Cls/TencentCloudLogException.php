<?php

namespace TencentCloud\Cls;
use Exception;

/**
 * The Exception to the log service request & response.
 *
 * @author farmerx
 */
class TencentCloudLogException extends Exception{
    /**
     * @var string
     */
    private $requestId;
    
    /**
     * TencentCloud_Log_Exception constructor
     *
     * @param string $code
     *            log service error code.
     * @param string $message
     *            detailed information for the exception.
     * @param string $requestId
     *            the request id of the response, '' is set if client error.
     */
    public function __construct($code, $message, $requestId='') {
        parent::__construct($message);
        $this->code = $code;
        $this->message = $message;
        $this->requestId = $requestId;
    }
    
    /**
     * The __toString() method allows a class to decide how it will react when
     * it is treated like a string.
     *
     * @return string
     */
    public function __toString() {
        return "TencentCloud_Log_Exception: \n{\n    ErrorCode: $this->code,\n    ErrorMessage: $this->message\n    RequestId: $this->requestId\n}\n";
    }
    
    /**
     * Get TencentCloud_Log_Exception error code.
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->code;
    }
    
    /**
     * Get TencentCloud_Log_Exception error message.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->message;
    }
    
    /**
     * Get log service sever request id, '' is set if client or Http error.
     *
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }
}

