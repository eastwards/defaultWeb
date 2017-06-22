<?php
/**
 * 项目统一配置
 */

defined('RootDir') or define('RootDir', dirname(__FILE__));

$web    = 'web';
$webDir = realpath(RootDir . "/{$web}");
$libDir = realpath(RootDir . '/Spring');

define('AppDir', RootDir . '/App');
define('ResourceDir', RootDir . '/Resource');
define('ConfigDir', RootDir . '/Config');
define('DataDir', RootDir . '/Data');

define('WebDir', $webDir);
define('LibDir', $libDir);

?>
