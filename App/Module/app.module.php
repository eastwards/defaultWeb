<?
/**
 * 应用业务组件基类
 *
 * 存放业务组件公共方法
 * 
 * @package	Model
 * @author	void
 * @since	2015-11-20
 */
abstract class AppModule extends Module
{
	protected $encoding;

    public function __construct()
    {
    	//自定义业务逻辑
		$this->getLoginUser();
        $this->encoding = $this->com('encoding');
    }

    /**
     * 解密队列数据
     *
     * @param   string      $data   队列加密数据
     * @return  array
     */
    public function decode($data, $encoding=0)
    {
        return $this->encoding->decode($data, $encoding);
    }

    /**
     * 加密队列数据
     *
     * @param   array   $data   队列数据
     * @return  string
     */
    public function encode($data, $encoding=0)
    {
        return $this->encoding->encode($data, $encoding);
    }

	public function getDb($dbName='')
	{
		if ( empty($dbName) ) return false;
		return new DbQuery($dbName);
	}

	/**
	 * 获取业务对象(系统对接时使用)
	 * @author	void
	 * @since	2015-11-20
	 *
	 * @access	public
	 * @param	string	$name	业务代理类名
	 * @return	object  返回业务对象
	 */
	public function importBi($name)
	{
		static $config = array();
		if ( empty($config) ) {
			require(ConfigDir.'/Extension/service.config.php');
		}
		
		static $objList = array();
		if ( isset($objList[$name]) && $objList[$name] ) {
			return $objList[$name];
		}

		$file = BiDir.'/'.strtolower($name).'.bi.php';
		require_once($file);
		$className      = $name.'Bi';
		$bi             = new $className();
		$bi->url        = $config[$bi->apiId]['url'];
		$objList[$name] = $bi;
		
		return $bi;
	}

	protected final function getLoginUser()
	{
		$userInfo = unserialize( Session::get(C('COOKIE_USER')) );
		return $this->setLoginUser($userInfo, $userInfo['remember']);
	}

	/**
	* 设置用户信息数据
	*
	* @author  Xuni
	* @since   2016-01-20
	* @access  public
	* @return  void
	*/
	protected final function setLoginUser($info, $remember='')
	{
		$this->isLogin 	= false;
		if ( empty($info) || empty($info['id']) ){
			return false;
		}
		$info['remember'] 	= $remember;
		
		$this->isLogin 		= true;
		$this->userId 		= $info['id'];
		$this->nickname 	= $info['nickname'];
		$this->username 	= $info['username'];
		$this->isUse 		= $info['isUse'];
		
		return true;
	}

}
?>