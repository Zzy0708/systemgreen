<?php
/**
 * Created by 图片检测.
 * User: zzy
 * Date: 2020/6/12
 * Time: 11:34
 */

namespace SystemGreen\CheckType;

use SystemGreen\AliyunGreen;


class GreenImg extends AliyunGreen {

    /**
     * 图片同步检测
     * @param $url ////指定检测对象，JSON数组中的每个元素是一个图片检测任务结构体（image表）。最多支持10个元素，即对10张图片进行检测。
     * @param string[] $scenes//默认：porn：图片智能鉴黄,terrorism：暴恐涉政识别等等。
     * @param array $extras //额外调用参数。
     * @return \AlibabaCloud\Client\Result\Result|array
     */
    public function imageScan($url, $scenes = array("porn", "terrorism"), $extras=array())
    {
        $tasks = $this->getTask($url, 'img');
        $body = array(
            'tasks' => $tasks,
            'scenes' => array("porn", "terrorism"),
        );
        if(!empty($extras)) {
            $body['extras'] = $extras;
        }
        return $this->response('/green/image/scan', $body);
    }

    /**
     * 图片异步检测
     * @param $url //指定检测对象，JSON数组中的每个元素是一个图片检测任务结构体（image表）。最多支持10个元素，即对10张图片进行检测。
     * @param string[] $scenes //默认：porn：图片智能鉴黄,terrorism：暴恐涉政识别等等。
     * @param null $seed //随机字符串，该值用于回调通知请求中的签名。当使用callback时，该字段必须提供。
     * @param $callback //异步检测结果回调通知您的URL，支持HTTP/HTTPS。该字段为空时，您必须定时检索检测结果。
     * @param array $extras //额外调用参数。
     * @return \AlibabaCloud\Client\Result\Result|array
     */
    public function imageAsyncscan($url, $scenes = array("porn", "terrorism"), $seed = null, $callback= null,$extras=array()) {
        $tasks = $this->getTask($url, 'img');

        $body = array(
            'tasks' => $tasks,
            'scenes' => $scenes,
            'seed'   => $seed,
            'callback' => $callback,
        );
        if(!empty($extras)) {
            $body['extras'] = $extras;
        }
        return $this->response('/green/image/asyncscan', $body);
    }

}