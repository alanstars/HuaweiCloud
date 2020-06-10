<?php
// +----------------------------------------------------------------------
// | CoolCms [ DEVELOPMENT IS SO SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 http://www.coolcms.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: Alan <251956250@qq.com>
// +----------------------------------------------------------------------
// | DateTime: 2020/6/10 12:48
// +----------------------------------------------------------------------
// | Desc: 
// +----------------------------------------------------------------------
require_once('../vendor/autoload.php');
$huawei = new \CoolElephant\HuaweiYun\HuaweiCloud('a1d1f50cad21415fbdd13d8f53d36d60','cfc881cc704c4fba8d8fef5788e03e6b');
$response = $huawei->option(['ignore_errors'=>true,'ssl'=>false])->uri('/rest/caas/relationnumber/partners/v1.0')->method('POST')->data([])->request();
var_dump($response);