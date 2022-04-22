<?php

namespace TencentCloud\Cls;

abstract class Callback{
    abstract function onCompletion(Result $result);
}