<?php
/**
 * Created by 项目名称.
 * User: zzy
 * Date: 2020/6/11
 * Time: 17:24
 */

require_once __DIR__ . './vendor/autoload.php';

use SystemGreen\AliyunGreen;

$obj = new AliyunGreen();

$msg = $obj->hello();
var_dump($msg);