<?php

namespace SystemGreen;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

use Config;

class AliyunGreen
{

    protected $accessKeyId;

    protected $accessKeySecret;

    protected $regionId;

    private  $debug;

    private  $timeout;

    private  $connectTimeout;

    public function __construct($accessKeyId = NULL,$accessKeySecret = NULL,$regionId='cn-shanghai', $debug = false, $timeout=6, $connectTimeout=10)
    {
        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;

        if(empty($this->accessKeyId)) {
            return ['code' => 0, 'msg' => 'accessKeyId不能为空'];
        }

        if(empty($this->accessKeySecret)) {
            return ['code' => 0, 'msg' => 'accessKeySecret不能为空'];
        }

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
                ->regionId($this->regionId)// 设置客户端区域，使用该客户端且没有单独设置的请求都使用此设置
                ->timeout($this->timeout)  // 超时10秒，使用该客户端且没有单独设置的请求都使用此设置
                ->connectTimeout( $this->connectTimeout)// 连接超时10秒，当单位小于1，则自动转换为毫秒，使用该客户端且没有单独设置的请求都使用此设置
                ->debug($this->debug) // 开启调试，CLI下会输出详细信息，使用该客户端且没有单独设置的请求都使用此设置
                ->asDefaultClient();
        } catch (Exception $e) {
            return ['code' => 0, 'msg' => $e->getErrorMessage()];
        }
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