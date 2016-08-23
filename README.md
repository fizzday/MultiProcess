# MultiProcess
the php multi process library depend on the pcntl php extension(php多进程类, 基于php的pcntl扩展)

## usage
### install
use composer
```
{
    {
        "fizzday/multiprocess": "dev-master"
    }
}
```
or
```
composer require fizzday/multiprocess
```
### using(english)
1. extends the `MultiProcess` abstract class
2. rewrite the `myAct()` method, do the real things you need
3. finally act run() method make it works

the code likes in file `myMulti.php`:
```
<?php
use Fizzday/MultiProcess/MultiProcess;

class MyMulti extends MultiProcess
{
    /**
     * @param $j the process num(第几个进程)
     * @param $i the circle num (第几次循环)
     */
    public function myAct($j, $i)
    {
        echo "this is the {$i} circle(循环) {$j} process(进程) \r\n";
        // example: 
        // suppose we have 100 rows data in total, we fetch 20 rows in on circle for 5 process
        // yet , we need circles: 100 / 20 = 5
        // we shell handle (20/5) rows in per process
        // suppose the datas in a array {$data), current process will handle the data index in the area:
        // from  {$i*20 + ($j)*(20/5)} to {$i*20 + ($j+1)*(20/5)}        
    }
}

$multi = new MyMulti(100, 20, 5);
$multi->run();
```
> suggestion: we'd better use the php-cli run this file like `php myMulti.php`

### using(中文)
1. 继承`MultiProcess`抽象类
2. 重写`myAct()`方法, 执行具体操作
3. 最后执行`run()`方法

创建代码文件 `myMulti.php`:
```
<?php
use Fizzday/MultiProcess/MultiProcess;

class MyMulti extends MultiProcess
{
    /**
     * @param $j 第几个进程
     * @param $i 第几次循环
     */
    public function myAct($j, $i)
    {
        echo "这是第 {$i} 次循环, 第 {$j} 个进程 \r\n";
        // 举例: 
        // 假设我们总共有100条数据, 每一次获取20条, 分5个进程来处理
        // 那么, 就需要(100/200)次循环来处理
        // 每个进程处理的数据条数为(20/5)
        // 假设我们取的数据在一个数组里, 那么当前进程所要操作的数据对应的编号区间为{$i*20 + ($j)*(20/5)}到{$i*20 + ($j+1)*(20/5)}  
    }
}

$multi = new MyMulti(100, 20, 5);
$multi->run();
```

> 建议: 最好使用命令行执行(`php myMulti.php`)

