<?php
/**
 * 应用入口文件
 * @copyright   Copyright(c) 2012
 * @author      cnluckylee <cnluckylee@gmail.com>
 * @version     1.0
 */
set_error_handler ( 'errorHandle' );
function errorHandle($errno, $errmsg, $filename, $linenum, $vars) {
	if ($errno == E_NOTICE) {
		return;
	}
	$file = '/tmp/spidermvc.err';
	$handle = fopen ( $file, 'a+' );
	fwrite ( $handle, $errmsg . '_' . $errno . '_' . $linenum . '_' . $filename . "\n" );
	fclose ( $handle );
}
require dirname(__FILE__).'/system/app.php';
require dirname(__FILE__).'/config/config.php';
error_reporting(E_ALL);
ini_set("display_errors",1);
Application::run($CONFIG);



