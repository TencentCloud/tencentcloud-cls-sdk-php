<?php

namespace TencentCloud\Cls;

use Exception;

/**
 * Class TencentCloud_Log_LoggerFactory
 * Factory for creating logger instance, with $client, $project, $logstore, $topic configurable.
 * Will flush current logger when the factory instance was recycled.
 */
class LoggerFactory{

    private static $loggerMap = array();

    /**
     * Get logger instance
     * @param Client $client valid log client
     * @param string|null $topicId which could be created in AliYun Logger Server configuration page
     * @return mixed return logger instance
     * @throws Exception if the input parameter is invalid, throw exception
     */
    public static function getLogger($client, $topicId, $callback, $maxCacheLog = null,
                                     $maxWaitTime = null, $maxCacheBytes = null) {
        if ($topicId == ''){
            throw new Exception('topic_id is blank!');
        }
        if (!array_key_exists($topicId, static::$loggerMap))
        {
            $instanceSimpleLogger = new SimpleLogger($client, $topicId, $callback, $maxCacheLog, $maxWaitTime, $maxCacheBytes);
            static::$loggerMap[$topicId] = $instanceSimpleLogger;
        }
        return static::$loggerMap[$topicId];
    }

    /**
     * set modifier to protected for singleton pattern
     * TencentCloud_Log_LoggerFactory constructor.
     */
    protected function __construct() {}

    /**
     * set clone function to private for singleton pattern
     */
    private function __clone() {}

    /**
     * flush current logger in destruct function
     */
    function __destruct() {
        if(static::$loggerMap != null){
            foreach (static::$loggerMap as $innerLogger){
                $innerLogger->logFlush();
            }
        }
    }
}
