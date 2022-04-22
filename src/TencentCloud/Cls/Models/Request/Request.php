<?php

namespace TencentCloud\Cls\Models\Request;

/**
 * The base request of all log request.
 *
 * @author farmerx
 */
class Request {

    /**
     * @var string topic id
     */
    private $topicId;

    /**
     * TencentCloud_Log_Models_Request constructor
     *
     * @param
     */
    public function __construct($topicId) {
        $this->topicId = $topicId;
    }
    
    /**
     * Get topic id
     *
     * @return string topic id
     */
    public function getTopicId()
    {
        return $this->topicId;
    }

    /**
     * Set topic id
     *
     * @param $topicId
     */
    public function setTopicId($topicId) {
        $this->topicId = $topicId;
    }
}
