<?php
/**
 * Created by 文本检测.
 * User: zzy
 * Date: 2020/6/12
 * Time: 11:36
 */

namespace SystemGreen\CheckType;

use SystemGreen\AliyunGreen;

class GreenText extends AliyunGreen {

    /**
     * 文本垃圾内容检测
     * @param $content
     * @return \AlibabaCloud\Client\Result\Result|array
     */
    public function textScan($content)
    {
        $tasks = $this->getTask($content,'text');
        /**
         * scenes    字符串数组 antispam。详情参考文档：https://help.aliyun.com/document_detail/70439.html
         * tasks    JSON数组 文本检测任务列表，包含一个或多个元素。每个元素是个结构体，最多可添加100个元素，即最多对100段文本进行检测
         */
        $body = array(
            'tasks' => $tasks,
            'scenes' => array("antispam")
        );
        return $this->response('/green/text/scan', $body);
    }



}