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
// | DateTime: 2020/6/9 16:25
// +----------------------------------------------------------------------
// | Desc: 
// +----------------------------------------------------------------------

namespace CoolElephant\HuaweiYun;


class HuaweiCloud
{
    /**
     * 当前请求的$appKey和$appSecret
     * @var array
     */
    private $appKey = null;
    private $appSecret = null;
    /**
     * 请求header头
     * @var null
     */
    private $header = null;
    /**
     * 请求方法：POST,GET,DELETE,PUT等
     * @var null
     */
    private $method = "POST";
    /**
     * 请求body数据，即请求参数
     * @var null
     */
    private $data = null;
    /**
     * 请求接口
     * @var null
     */
    private $uri = null;
    /**
     * 额外设置
     * @var null
     */
    private $option = false;
    /**
     * 拼接真实请求数据
     * @var null
     */
    private $requestData = null;
    /**
     * Version of HuaweiCloud
     */
    const VERSION = '1.0.3';
    /**
     * 华为接口请求域名
     */
    private $domian = 'https://rtcapi.cn-north-1.myhuaweicloud.com:12543';

    /**
     * 初始化 SDK，并传入账号密码
     * HuaweiCloud constructor.
     * @param string $appKey       应用AppKey
     * @param string $appSecret    应用appsecret
     * @param string $domian       如果请求不是默认的域名，请自定义传入
     */
    public function __construct($appKey='',$appSecret='',$domian = '')
    {
        if(!empty($domian)){
            $this->domian = $domian;
        }
        if(!empty($appKey) && !empty($appSecret)){
            $this->appKey = $appKey;
            $this->appSecret = $appSecret;
            //设置header头
            $this->header = $this->buildWsseHeader();
        }else{
            echo "未设置appKey或appSecret";
            die;
        }
    }

    /**
     * 执行请求，并返回结果
     * @return bool|\Exception|string
     */
    public function request(){
        //设置完整请求参数
        $this->requestData = $this->requestData();
        try{
            $response = file_get_contents($this->domian.$this->uri,false,stream_context_create($this->requestData));
            return $response;
        }catch (\Exception $e){
            return $e;
        }

    }

    /**
     * 拼接完整请求参数
     * @return array
     */
    private function requestData(){
        $data = [
            'http' => [
                'method' => 'POST', // 请求方法为POST
                'header' => $this->header,
                'content' => json_encode($this->data),
                'ignore_errors' => true // 返回json格式
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ] // 为防止因HTTPS证书认证失败造成API调用失败,需要先忽略证书信任问题
        ];
        if($this->option){
            $data['ssl'] = [
                'verify_peer' => true,
                'verify_peer_name' => true
            ];
        }
        return $data;
    }

    /**
     * 设置header头
     * @return string
     */
    private function buildWsseHeader()
    {
        if(!empty($this->appKey) && !empty($this->appSecret)){
            date_default_timezone_set("UTC");
            $time = date('Y-m-d\TH:i:s\Z');
            $nonce = uniqid();
            $signature = base64_encode(hash('sha256',($nonce.$time.$this->appSecret),true));
            $wsse =  sprintf("UsernameToken Username=\"%s\",PasswordDigest=\"%s\",Nonce=\"%s\",Created=\"%s\"", $this->appKey, $signature, $nonce, $time);
            $headers = [
                'Accept: application/json',
                'Content-Type: application/json;charset=UTF-8',
                'Authorization: WSSE realm="SDP",profile="UsernameToken",type="Appkey"',
                'X-WSSE: ' . $wsse
            ];
            return $headers;
        }else{
            return '未设置appKey或appSecret';
        }
    }

    /**
     * 设置请求的方式
     * @param $method
     * @return $this
     */
    public function method($method){
        $this->method = $method;
        return $this;
    }

    /**
     * 设置请求的接口
     * @param $uri
     * @return $this
     */
    public function uri($uri){
        $uri = trim($uri);
        if(strpos($uri,'/') !== 0){
            $uri = '/'.trim($uri);
        }
        $this->uri = $uri;
        return $this;
    }
    /**
     * 设置请求参数
     * @param $data array
     * @return $this
     */
    public function data($data = []){
        $this->data = $data;
        return $this;
    }

    /**
     * 设置额外配置参数，是否开启ssl，默认为false
     *
     * @param array $option
     * @return $this
     */
    public function option($option = false){
        $this->option = $option;
        return $this;
    }
}