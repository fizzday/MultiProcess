<?php

namespace Fizzday\MultiProcess;

class MultiProcess
{
    /**
     * total process (总进程数)
     * @var int
     */
    public static $process=10;

    /**
     * 设置进程数
     * @param int $process
     * @return MultiProcess
     */
    public static function setProcess($process=10)
    {
        self::$process = $process;
        return new self();
    }

    /**
     * 多进程任务开始调用方法
     * @param $act
     */
    public static function run($act)
    {
        if (!function_exists('pcntl_fork')) return 'pcntl extension is not exists, please install it frstly';
        // 定义记录子进程的数组
        $pids = array();

        for ($i = 0; $i < self::$process; $i++) {

            $pids[$i] = pcntl_fork();

            switch ($pids[$i]) {
                case -1:
//                    echo "开启子进程失败 : {$i} \r\n";
                    echo "fork process failed : {$i} \r\n";
                    exit;

                case 0:
                    // 开启子进程成功, 做相应操作
                    $act($i);
                    exit;

                default:
                    break;
            }

        }

        // 等待子进程退出并回收
        foreach ($pids as $k => $pid) {
            if ($pid) {
                pcntl_waitpid($pid, $status);
            }
        }
    }
}

