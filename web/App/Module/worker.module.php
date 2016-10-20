<?
if (php_sapi_name() != "cli") {
    exit("Only run in command line mode \n");
}
/**
 * worker 多进程处理类
 *
 * 多进程处理对应功能类
 * 
 * @package     Module
 * @author      Xuni
 * @since       2016-10-18
 */
class WorkerModule extends AppModule
{
    protected $workerId     = 0;
    protected $runningKey   = '_trade_worker_' ;
    protected $cacheType    = 'redisQc';//缓存类型
    protected $queueType    = 'redisQ';//队列类型

    protected $worker ;//worker对象
    protected $workerName ;//worker类
    protected $workerMethod ;//worker要执行的方法
    protected $queue ;//队列对象
    protected $queueName ;//队列对象


    const COUNT = 10 ;

    public function before()
    {
        //可以在超时后执行，destruct无法执行。
        register_shutdown_function(array(&$this,'destroy'));

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exit('Script does not support Windows');
        }

        $this->objC = $this->com($this->cacheType);//获取缓存资源
        $this->objQ = $this->com($this->queueType);//获取队列资源

        if ( !is_object($this->objC) || !is_object($this->objQ) ) exit('Queue cache error');

        $this->messageList = array(
            '101' => 'model error or model is not object',
            '102' => 'method error',
            '103' => 'method has not',
            '104' => 'execute failed',
            '201' => 'execute success',
        );
    }

    protected function loadWorker($worker, $method, $queueName)
    {
        if ( empty($worker) ) exit('No worker');
        if ( empty($method) ) exit('No method');
        if ( empty($queueName) ) exit('No queueName');

        $this->worker       = $this->load($worker);
        if ( !method_exists($this->worker, $method) ) exit('Worker has not method');

        $this->workerName   = $worker;
        $this->workerMethod = $method;
        $this->queue        = $this->objQ->name($queueName);
        $this->queueName    = $queueName;
    }

    public function run($worker, $method, $queueName, $count=5)
    {
        $this->loadWorker($worker, $method, $queueName);
        $this->saveMasterPid();

        $count      = (intval($count) > 10 || intval($count) <= 0) ? self::COUNT : intval($count) ;
        $total      = 0;
        $timeout    = 5;
        while (1){
            $size   = $this->queue->size();
            if ( $size == 0 ) {
                if ( $total <= 0 ) {
                    sleep(1); $timeout--;
                    if ( $timeout <= 0 ) break; //超时5秒，且没有队列数据与子进程在运行
                    continue;
                }

                pcntl_wait($status);
                $total--; usleep(100000); continue;
            }
            $total++;
            $timeout    = 5;
            $pid        = pcntl_fork();

            if ( $pid == -1 ) {
                exit('Could not fork');
            }elseif ( $pid ){
                if ( $total >= $count ){
                    pcntl_wait($status);
                    $total--;
                }
            }else{
                $data = $this->queue->pop();
                $flag = call_user_func( array($this->worker, $this->workerMethod), $data );
                if ( $flag === false ){
                    //TODO 失败是否重新丢入
                }
                exit(0);
            }
        }
    }

    protected function saveMasterPid()
    {
        $this->_pidFile     = LogDir.'/'.$this->runningKey.$this->workerName.'_'.$this->workerMethod.'.pid' ;
        $this->_masterPid   = posix_getpid();

        if (false === @file_put_contents($this->_pidFile, $this->_masterPid)) {
            throw new Exception('can not save pid to ' . $this->_pidFile);
        }
    }

    protected function destory()
    {
        $this->worker       = null;
        $this->workerName   = null;
        $this->workerMethod = null;
        $this->queue        = null;
        $this->queueName    = null;

        @unlink($this->_pidFile);
    }

}
?>