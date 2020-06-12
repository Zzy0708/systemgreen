<?php
/**
 * Created by 视频检测.
 * User: zzy
 * Date: 2020/6/12
 * Time: 11:52
 */

namespace SystemGreen\CheckType;

use SystemGreen\AliyunGreen;


class GreenVideo extends AliyunGreen {


    /**
     * 视频同步检测:视频同步检测接口只支持通过上传视频截帧图片的方式进行检测。如果您想通过上传视频URL的方式进行检测，使用异步检测接口。
     * @param $data
     * @param string[] $scenes //默认：porn：智能鉴黄,terrorism：暴恐涉政识别等等。
     * @return \AlibabaCloud\Client\Result\Result|array
     */
    public function videoSyncscan($data, $scenes = array("porn", "terrorism"))
    {
        if(!is_array($data)) {
            return ['code' => 0, 'msg' => 'data格式错误'];
        }

        /**
         * data:
         *     $data = [
         *          [
         *              'offset' => 10,//该截帧距离片头的时间戳，单位为秒
         *              'url'    => http://g1.ykimg.com/0B860000586C0A0300038A0460000 //截帧地址
         *          ]
         *     ];
         *
         */
        $tasks = [];
        foreach ($data as $k => $v) {
            $tasks[] = [
                'dataId' => uniqid(),
                'frames' => $v,
            ];
        }
        $body = array(
            'tasks' => $tasks,
            'scenes' => $scenes,
        );
        return $this->response('/green/video/syncscan', $body);
    }

    /**
     * 视频异步检测:
     * @param $url
     * @param string[] $scenes //默认：porn：智能鉴黄,terrorism：暴恐涉政识别等等。
     * @param null $seed       //随机字符串，该值用于回调通知请求中的签名。当使用callback时，该字段必须提供。
     * @param null $callback    //异步检测结果回调通知您的URL，支持HTTP/HTTPS。该字段为空时，您必须定时检索检测结果。
     * @param array $audioScenes //选择一个或多个语音检测场景，在检测视频中图像的同时，对视频中语音进行检测
     * @param bool $live         //是否直播。默认为false，表示为普通视频检测；若为直播检测，该值必须传入true。
     * @param bool $offline//是否近线检测模式。默认为false，表示实时检测模式，对于超过了并发路数限制的检测请求会直接拒绝。如果为true，会进入近线检测模式，提交的任务不保证实时处理，但是可以排队处理，在24小时内开始检测。
     * @return \AlibabaCloud\Client\Result\Result|array
     */
    public function videoAsyncscan($url, $scenes = array("porn", "terrorism"),$seed = null, $callback= null ,$audioScenes = array(),$live=false , $offline =false) {
        $tasks = $this->getTask($url,'video');
        $body = array(
            'tasks' => $tasks,
            'scenes' => $scenes,
            'live'   => $live,
            'offline' => $offline,
            'seed'   => $seed,
            'audioScenes' => $audioScenes,
            'callback' => $callback,
        );
        return $this->response('/green/video/asyncscan', $body);
    }



    /**
     * 查询视频异步检测结果
     * @param $body //JSON数组 要查询的taskId列表。最大长度不超过100。
     */
    public function videoResults($body) {
        $body = $this->generateArray($body);
        return $this->response('/green/video/results', $body);
    }

    /**
     * 停止检测
     * @param $body //JSON数组 要查询的taskId列表。最大长度不超过100。
     */
    public function videoCancelscan($body) {
        $body = $this->generateArray($body);
        return $this->response('/green/video/cancelscan', $body);
    }

}