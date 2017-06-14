<?
$cmdDir = dirname(__FILE__);                            //获取当前路径
require($cmdDir . '/init.php');
require_once(LibDir . '/Spring.php');                     //载入框架入口文件
require_once(LibDir . '/Util/Tool/MakeCode.php');         //载入代码生成工具

//指定数据库名、表前缀
$configs = array(
    array(
        'name'      => 'useradmin',
        'db'        => 'useradmin',
        'prefix'    => 'ua_',
        'contain'   => '*',
        // 'contain'   => array(
        //     'tm_imgurl',
        //     'tm_proposer',
        //     ),
        ),
    );
Spring::init();
//指定数据库配置文件存放路径
MakeCode::$configFileDir = WebDir.'/Config/Db';
MakeCode::create($configs);
?>