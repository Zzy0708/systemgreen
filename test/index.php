<?php
/**
 * Created by 示例.
 * User: zzy
 * Date: 2020/6/12
 * Time: 14:58
 */

require_once __DIR__ . '../../vendor/autoload.php';
use SystemGreen\AliyunGreen;
$obj = new AliyunGreen('accessKeyId','accessKeySecret');

$msg = $obj->textScan("傻B");
var_dump($msg);