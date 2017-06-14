<?php
/**
 * 项目入口
 */
set_time_limit(0);                                          //设置超时时间
$cmdDir = dirname(__FILE__);
require($cmdDir . '/init.php');

define('ActionDir', AppDir.'/Console');                     //定义控制器存放路径
require(LibDir . '/Spring.php');                              //载入框架入口文件
require(ConfigDir . '/app.config.php');                       //载入应用全局配置

return Spring::out();

?>
