<?php

require __DIR__ . '/../src/MultiProcess.php';
use Fizzday\MultiProcess\MultiProcess;

//echo time();
// 设置将要开启的进程数
$process = 100;
MultiProcess::setProcess($process)->run(function ($p) use ($process) {
    // 数据总量
    $total = 10000;
    // 每个进程对应要处理的数据总量
    $perProcessNum = $total / $process;
    // 开始处理数据的索引(包含开头)
    $start = $p * $perProcessNum;
    // 结束处理数据的索引(不包含结尾)
    $end   = $start + $perProcessNum;

    // 模拟将要处理的数据
//    $data = array();
//    for ($a = 0; $a < $total; $a++) {
//        $data[] = $a . 'aa';
//    }

    // 开始处理数据
    $data_return = '';
    for ($i = $start; $i < $end; $i++) {
        $mobile = 13;
        $mobile .= mt_rand(10000, 99999).mt_rand(1000, 9999);

        $res = sendsms($mobile);

        $data_return .= $res.'-进程编号:' . $p . '-处理数据:' . $mobile . PHP_EOL;
    }

    $data_return .= PHP_EOL;

    $file = './log2.txt';
    file_set($file, $data_return);

    // 实时输出
    echo $data_return;
    // 如果想接收返回值, 就去掉输出, 改为返回
//    return $data_return;
});

function file_set($file, $data, $mode = 'a')
{
    if (!$file || !$data) return false;

//    $dir = dirname($file);
//
//    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $fp = fopen($file, $mode);
    fwrite($fp, $data);
    fclose($fp);

    chmod($file, 0777);

    return true;
}

//https://www.jppatt.com/open/SendForRegister
//country:86
//phone:13212341123
function sendsms($mobile)
{
    $url  = 'https://www.jppatt.com/open/SendForRegister';
    $data = array(
        "country" => "86",
        "phone"   => $mobile
    );

    $res = send_post($url, $data);

    return $res;
}


function send_post($url, $post_data) {
    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return $result;
}