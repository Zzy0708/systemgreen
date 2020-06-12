<?php

namespace SystemGreen;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class AliyunGreen
{

    private $accessKeyId;

    private $accessKeySecret;

    private $regionId;

    private  $debug;

    private  $timeout;

    private  $connectTimeout;

    public function __construct($accessKeyId = NULL,$accessKeySecret = NULL,$regionId='cn-shanghai', $debug = false, $timeout=6, $connectTimeout=10)
    {

        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
        $this->regionId = $regionId;
        $this->debug = $debug;
        $this->timeout = $timeout;
        $this->connectTimeout = $connectTimeout;

        $this->__initialization();
    }


    /**
     * 初始化
     * @throws ClientException
     */
    public  function  __initialization() {
        try {
             AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)
                ->regionId($this->regionId)// 设置客户端区域，
                ->timeout($this->timeout)  // 超时10秒，使用该客户端且没有单独设置的请求都使用此设置
                ->connectTimeout( $this->connectTimeout)// 连接超时10秒
                ->debug($this->debug) // 开启调试
                ->asDefaultClient();
        } catch (Exception $e) {
            return ['code' => 0, 'msg' => $e->getErrorMessage()];
        }
    }


    /**
     * 提交语音检测任务
     * @param $content
     * @return \AlibabaCloud\Client\Result\Result|array
     */
    public function voiceAsyncscan($url, $scenes = array("antispam"),$seed = null, $callback= null,$live=false , $offline =false) {
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
     * 查询音频异步检测结果
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


    /**
     * @param $url  //提交文件检测任务
     * @param null $textScenes    //检测内容包含文本时，指定检测场景，取值：antispam。
     * @param null $imageScenes  //检测内容包含图片时，指定检测场景，默认：porn：智能鉴黄,terrorism：暴恐涉政识别等等。
     * @param null $callback    //异步检测结果回调通知您的URL，支持HTTP/HTTPS。
     * @param null $seed        //该值用于回调通知请求中的签名
     * @return \AlibabaCloud\Client\Result\Result|array
     */
    public function fileAsyncscan($url, $textScenes = null, $imageScenes=null ,$callback=null,$seed=null)
    {
        $tasks = $this->getTask($url, 'file');
        $body = array(
            'tasks' => $tasks,
            'callback' => $callback,
            'seed' => $seed
        );
        if(empty($textScenes)) {
            $body['textScenes'] = array("antispam");
        }
        if(empty($textScenes)) {
            $body['imageScenes'] = array("porn","terrorism");
        }
        return $this->response('file/asyncscan', $body);
    }

    /**
     * 提交文件检测任务后，调用本接口查询检测结果。
     * @param $body
     * @return \AlibabaCloud\Client\Result\Result|array
     */
    public function fileResults($body)
    {
        $body = $this->generateArray($body);
        return $this->response('/green/file/results', $body);
    }


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


    /**
     * 请求api
     * @param $action
     * @param $body
     * @param $params
     * @return \AlibabaCloud\Client\Result\Result|array
     */
    protected function response($action, $body, $params = [])
    {
        try {
            $result = AlibabaCloud::roaRequest()
                ->product('Green')
                ->version('2018-05-09')
                ->pathPattern($action)
                ->method('POST')
                ->options([
                    'query' => $params
                ])
                ->body(json_encode($body))
                ->request();
            if ($result->isSuccess()) {
                return $result->toArray();
            } else {
                return $result;
            }
        } catch (ClientException $e) {
            return ['code' => 0, 'msg' => $e->getErrorMessage()];
        } catch (ServerException $e) {
            return ['code' => 0, 'msg' => $e->getErrorMessage()];
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function generateArray($data)
    {
        $urls = [];
        if(!is_array($data)) {
            $res = json_decode($data, true);
            if(is_null($res)) {
                $urls[] = $data;
            } else {
                $urls = $res;
            }
        } else {
            $urls = $data;
        }
        return $urls;
    }

    /**
     * @param $data
     * @param string $type
     * @return array
     */
    public function getTask($data, $type='img') {
        $tasks =[];
        $urls = $this->generateArray($data);
        foreach ($urls as $k => $v) {
            $arr = array( 'dataId' => uniqid());
            if($type == 'text') {
                $arr['content'] = $data;
            } else if(in_array($type,array('img','file'))) {
                $arr['url'] = $data;
            } else if($type == 'video') {
                $arr['url'] = $data;
                $arr['interval'] = 1;
                $arr['maxFrames'] = 200;
            }
            $tasks[] = $arr;
        }
        return  $tasks;
    }

}