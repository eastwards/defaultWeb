<?
/**
 * 后台任务控制器
 *
 * 执行后台任务
 *
 * @package	Action
 * @author	void
 * @since	2015-11-20
 */
class TaskAction extends ConsoleAction
{	
	/**
	 * 执行后台任务
	 * @author	void
	 * @since	2015-11-20
	 *
	 * @access	public
	 * @return	void
	 */
	public function work()
	{
		print time();
	}

	public function queue()
	{
		for ($i=0; $i < 100; $i++) { 
			
			$data 	= array(time());
			$method = rand(100, 10000);
			$this->load('queuelib')->addQueue($method, $data, 'test123');

		}
	}

	public function fork()
	{
		umask(0);
		$obj = $this->load('worker');
		// $redis = new Redis();
		// $redis->connect('127.0.0.1', 6379);
		// $redis->select(9);
		// $redis->setex('bbbb', 60, $obj->pid);
		// $redis->close();
		$this->com('redisQc')->set('bbbb', 123, 60, 0);
		//echo $this->com('redisQc')->get('bbbb',0);
		//$this->com('redisQc')->close();
		//exit('~~~123');

		$obj->setWorker('test', 'run')->run(3);
		$obj->wait();

		// $redis = new Redis();
		// $redis->connect('127.0.0.1', 6379);
		// $redis->select(9);
		//$this->com('redisQc')->remove('aaaa');
		sleep(1);
		$this->com('redisQc')->remove('bbbb', 0);
		// $redis->delete('bbbb');
		echo "\n fork finish... \n";
	}
}
?>