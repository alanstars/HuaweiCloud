## HuaweiCloud
华为云接口封装，支持华为云平台通用接口

## 安装方法
```
composer require coolelephant/huaweicloud
```
## 使用方法
本方法支持命名空间，如您的项目支持自动加载，直接实例化即可
```
$huaweiCloud = new \CoolElephant\HuaweiYun\HuaweiCloud('a1d1f50cad21415fbdd13d8f53d36d60','cfc881cc704c4fba8d8fef5788e03e6b');
$response = $huaweiCloud->option(['ignore_errors'=>true,'ssl'=>false])
                        ->uri('/rest/caas/relationnumber/partners/v1.0')
                        ->method('POST')
                        ->data(['relationNum' => 1888888888,'callerNum' => 18666666666])
                        ->request();
```

如您的项目不支持自动加载，则需要再上面的基础上引入
```
require_once('../vendor/autoload.php');
```