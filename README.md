# 阿里云内容安全扩展包

## 安装

```composer  require zzy0708/systemgreen ```

## 使用

```
use SystemGreen\CheckType\GreenText;
use SystemGreen\CheckType\GreenImg;
use SystemGreen\CheckType\GreenVideo;
use SystemGreen\CheckType\GreenFile;
 
$obj = new GreenText('accessKeyId','accessKeySecret');
$msg = $obj->textScan("傻B");
print_r($msg);die;
```
