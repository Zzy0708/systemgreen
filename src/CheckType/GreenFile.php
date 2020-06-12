<?php
/**
 * Created by 文件检测.
 * User: zzy
 * Date: 2020/6/12
 * Time: 11:36
 */

namespace SystemGreen\CheckType;

use SystemGreen\AliyunGreen;

class GreenFile extends AliyunGreen {

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

}