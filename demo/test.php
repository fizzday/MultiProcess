<?php

require __DIR__ . '/../vendor/autoload.php';
use Fizzday\MultiProcess\MultiProcess;

class MyMulti extends MultiProcess
{
    /**
     * 集成后重写的具体操作
     * @param $i num 第几次循环
     * @param $j num 本次循环的第几个进程
     */
    public function myAct($j, $i)
    {
//        $b = 0;
        for ($a = 0; $a < ($this->pcntl_perTotalNum / $this->pcntl_num); $a++) {
//            $this->b ++;
            $requestUrl = 'http://www.test.com';
            $post_data  = ['phone' => '132123' . mt_rand(10000, 99999)];
            $aa         = explode("\r\n", curl_post($requestUrl, $post_data));
//        echo $i.' -- '.$aa[0]."\r\n";
            $bb = $j*$i+$a;
            echo "{$bb} -- 第 $i 次({$post_data['phone']})执行, 第 $a 次操作, 第 $j 个进程 -- " . $aa[0] . "\r\n";
        }


    }

}

// 开始使用
$myPcntl = new MyMulti(100000, 10000, 100);

$myPcntl->run();

//$proxy = [
//    'ip'    => '112.65.219.'.mt_rand(1, 255),
//    'port'  => '80'
//];

//$ch = curl_init();
//$timeout = 5;
//curl_setopt($ch, CURLOPT_URL, $requestUrl);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
//curl_setopt($ch, CURLOPT_PROXY, "112.65.219.72"); //代理服务器地址
//curl_setopt($ch, CURLOPT_PROXYPORT, 80); //代理服务器端口
////curl_setopt($ch, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
//// 添加post数据到请求中
//curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//$file_contents = curl_exec($ch);
//curl_close($ch);
//echo $file_contents;


//for ($i=0; $i<20000; $i++) {
//    $aa = explode("\r\n", curl_post($requestUrl, $post_data));
//    echo $i.' -- '.$aa[0]."\r\n";
//}


function curl_post($url, $post_data, $proxy = [])
{
    //初始化一个 cURL 对象
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//    if (!empty($proxy)) {
//        curl_setopt($ch,CURLOPT_PROXY,$proxy['ip']);
//        curl_setopt($ch,CURLOPT_PROXYPORT,$proxy['port']);
//    }

    // 设置请求为post类型
    curl_setopt($ch, CURLOPT_POST, 1);
    // 添加post数据到请求中
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    // 执行post请求，获得回复
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}


function send_post($url, $post_data)
{

    $postdata = http_build_query($post_data);
    $options  = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context  = stream_context_create($options);
    $result   = file_get_contents($url, false, $context);

    return $result;
}