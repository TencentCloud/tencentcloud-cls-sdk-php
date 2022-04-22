<?php

namespace TencentCloud\Cls\Models\Response;

/**
 * The response of the PutLogs API from log service.
 *
 * @author log service dev
 */
class PutLogsResponse extends Response {
    /**
     * TencentCloud_Log_Models_PutLogsResponse constructor
     *
     * @param $headers
     */
    public function __construct($headers) {
        parent::__construct ( $headers );
    }
}
