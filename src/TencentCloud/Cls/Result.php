<?php

namespace TencentCloud\Cls;

class Result {
    /**
     * @var boolean
     */
    private $successful;

    public function isSuccessful()
    {
        return $this->successful;
    }


    /**
     * @var string
     */
    private $request_id;

    public function getRequestId()
    {
        return $this->request_id;
    }

    /**
     * @var string
     */
    private $error_message;

    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @var string
     */
    private $error_code;

    public function getErrorCode()
    {
        return $this->error_code;
    }


    /**
     * @var int
     */
    private $retry_times;

    public function getRetryTimes()
    {
        return $this->retry_times;
    }

    public function __construct($successful, $request_id = '', $retry_times = 0, $error_code = '', $error_message = '' )
    {
        $this->successful = $successful;
        $this->request_id = $request_id;
        $this->retry_times = $retry_times;
        $this->error_code = $error_code;
        $this->error_message = $error_message;
    }
}
