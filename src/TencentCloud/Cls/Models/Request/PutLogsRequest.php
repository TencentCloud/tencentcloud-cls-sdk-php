<?php

namespace TencentCloud\Cls\Models\Request;

/**
 * The request used to send data to log server.
 *
 * @author log service dev
 */
class PutLogsRequest extends Request {

    /**
     * @var string source of the logs
     */
    private $source;

    /**
     * @var array LogItem array, log data
     */
    private $logItems;

    /**
     * @var string shardKey put logs shard hash key
     */
    private $shardKey;

    /**
     * TencentCloud_Log_Models_PutLogsRequest constructor
     *
     * @param string $topicId
     *            topic id
     * @param string|null $source
     *            source of the log
     * @param array|null $logItems
     *            LogItem array,log data
     */
    public function __construct($topicId = null, $source = null, $logItems = null, $shardKey=null) {
        parent::__construct ( $topicId );
        $this->source = $source;
        $this->logItems = $logItems;
        $this->shardKey = $shardKey;
    }



    /**
     * Get all the log data
     *
     * @return array LogItem array, log data
     */
    public function getLogItems()
    {
        return $this->logItems;
    }

    /**
     * Set the log data
     *
     * @param array $logItems
     *            LogItem array, log data
     */
    public function setLogItems(array $logItems) {
        $this->logItems = $logItems;
    }
    
    /**
     * Get log source
     *
     * @return string log source
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * set log source
     *
     * @param string $source
     *            log source
     */
    public function setSource($source) {
        $this->source = $source;
    }
    /**
     * set shard key
     *
     * @param string sharded
     */
    public function setShardKey($key){
        $this -> shardKey=$key;
    }
    /**
     * get shard key
     *
     * @return string shardKey
     */
    public function getShardKey()
    {
        return $this ->shardKey;
    }
}
