<?php
/**
 * Created by 文本检测.
 * User: zzy
 * Date: 2020/6/12
 * Time: 11:36
 */

namespace SystemGreen\CheckType;

use SystemGreen\AliyunGreen;

class GreenAudio extends AliyunGreen {

    /**
     * 提交语音检测任务
     * @param $content
     * @return \AlibabaCloud\Client\Result\Result|array
     */
    public function videoAsyncscan($url, $scenes = array("antispam"),$seed = null, $callback= null,$live=false , $offline =false) {
        $tasks = $this->getTask($url,'video');
        $body = array(
            'tasks' => $tasks,
            'scenes' => $scenes,
            'live'   => $live,
            'offline' => $offline,
            'seed'   => $seed,
            'callback' => $callback,
        );
        return $this->response('/green/voice/asyncscan', $body);
    }



    /**
     * 查询视频异步检测结果
     * @param $body //JSON数组 要查询的taskId列表。最大长度不超过100。
     */
    public function voiceResults($body) {
        $body = $this->generateArray($body);
        return $this->response('/green/voice/results', $body);
    }

    /**
     * 停止检测
     * @param $body //JSON数组 要查询的taskId列表。最大长度不超过100。
     */
    public function voiceCancelscan($body) {
        $body = $this->generateArray($body);
        return $this->response('/green/voice/cancelscan', $body);
    }


}