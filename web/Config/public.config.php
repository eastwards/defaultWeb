<?
/**
 * 定义应用所需常量
 */
$checkhost 	= include(ConfigDir.'/checkhost.config.php');
$define = array(
	'COOKIE_USER' => 'UsErAdMiNyZc',
	
);
if(is_array($checkhost)){
	$define = array_merge($define,$checkhost);	
}

return $define;

?>