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
			$this->load('queuelib')->addQueue('test', $data, 'test123');

		}
	}

	public function fork()
	{
		$this->load('worker')->run('test', 'run', 'tradeQueue');
	}
}
?>