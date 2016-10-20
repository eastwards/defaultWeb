<?
/**
 * 队列处理
 *
 * 处理队列方法
 * 
 * @package	Module
 * @author	Xuni
 * @since	2015-06-18
 */
class TestModule extends AppModule
{
    
    public function run($data)
    {        
        error_log('run start '.date('Y-m-d H:i:s'), 3, LogDir.'/test.log');
        sleep(rand(2,5));
        return true;
    }

    
}
?>