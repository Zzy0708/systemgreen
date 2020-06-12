# 阿里云内容安全扩展包

## 安装

```composer  require zzy0708/systemgreen ```

## 引用

```
use SystemGreen\AliyunGreen;
$obj = new AliyunGreen('accessKeyId','accessKeySecret');
```

## 文本内容检测

```
$msg = $obj->textScan("傻*");//支持数组,json,字符串格式，最多不超过100个
var_dump($msg);
```



## 图片同步内容检测

```
$data = 'http://test.com/1.jpg';//支持数组,json,字符串格式
$scenes = array(
			'porn',//鉴黄
			'terrorism',//暴恐涉政
			'ad',//广告
			'qrcode',//二维码
			'live',//不良场景。黑屏、黑边、昏暗画面、画中画、抽烟、打架等不良场景
			'logo',//logo，台标，商标等
		);//默认porn,terrorism
$msg = $obj->imageScan($data,$scenes);
var_dump($msg);
```

## 图片异步内容检测

```
$data = 'http://test.com/1.jpg';//支持数组,json,字符串格式，最多不超过10个。
$scenes = array(
			'porn',//鉴黄
			'terrorism',//暴恐涉政
		);//默认porn,terrorism
$seed = '';//回调需要的签名
$callback = '';//异步检测完成后的回调地址	
$extras = '';//额外参数，例如OCR识别，人脸等等。请参考阿里云手册	
$msg = $obj->imageAsyncscan($data,$scenes,);
var_dump($msg);
```

## 视频同步检测

```
	/**
	   * 视频同步检测:视频同步检测接口只支持通过上传视频截帧图片的方式进行检测。如果您想通过上传视频URL的方式进行检测，使用异步检测接口。
	   * @param $data
	   * @param string[] $scenes //默认：porn：智能鉴黄,terrorism：暴恐涉政识别等等。
	   * @return \AlibabaCloud\Client\Result\Result|array
	   */
  

	/**
	  *   $data = [
	  *               [
	  *                   'offset' => 10,//该截帧距离片头的时间戳，单位为秒
	  *		              'url'    => http://g1.ykimg.com/0B860000586C0A0300038A0460000 //截帧地址
	  *		          ]
	  *			  ];
	  *
	  */  
$msg = $obj->videoSyncscan($data, $scenes = array("porn", "terrorism"));
var_dump($msg);
```


## 视频异步检测

```
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
    
$msg = $obj->videoAsyncscan($data, $scenes = array("porn", "terrorism"));
var_dump($msg);
```


##  查询视频异步检测结果

```
$taskId = "";taskId值
$msg = $obj->videoResults($taskId);
var_dump($msg);
```

##  停止检测

```
$taskId = "";taskId值
$msg = $obj->videoResults($taskId);
var_dump($msg);
```

## 文件异步检测

```
 /**
     * @param $url  //提交文件检测任务
     * @param null $textScenes    //检测内容包含文本时，指定检测场景，取值：antispam。
     * @param null $imageScenes  //检测内容包含图片时，指定检测场景，默认：porn：智能鉴黄,terrorism：暴恐涉政识别等等。
     * @param null $callback    //异步检测结果回调通知您的URL，支持HTTP/HTTPS。
     * @param null $seed        //该值用于回调通知请求中的签名
     * @return \AlibabaCloud\Client\Result\Result|array
     */
$msg = $obj->fileAsyncscan($data);
var_dump($msg);
```

## 提交文件检测任务后，调用本接口查询检测结果。


```
$taskId = "";taskId值
$msg = $obj->fileResults($taskId);
var_dump($msg);
```


## 提交语音检测任务

```
  /**
     * 提交语音检测任务
     * @param $url
     * @param string[] $scenes
     * @param null $seed
     * @param null $callback
     * @param bool $live
     * @param bool $offline
     * @return \AlibabaCloud\Client\Result\Result|array
     */
$msg = $obj->voiceAsyncscan($data);
var_dump($msg);
```


##  查询音频异步检测结果

```
$taskId = "";taskId值
$msg = $obj->voiceResults($taskId);
var_dump($msg);
```

##  停止检测

```
$taskId = "";taskId值
$msg = $obj->voiceResults($taskId);
var_dump($msg);
```