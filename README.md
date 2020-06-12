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
$msg = $obj->textScan("傻*");
var_dump($msg);
```
