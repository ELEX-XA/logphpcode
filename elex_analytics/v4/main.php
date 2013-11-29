<?php
/*
 * save log
 */
if(!defined('XA_LOG_RETURN_TYPE')){
	define('XA_LOG_RETURN_TYPE', "php");
}
if(!defined('XA_LOG_SAVE_TYPE')){
	define('XA_LOG_SAVE_TYPE', "index");
}

require_once GLOBAL_LIB_ROOT. '/common.php';
require_once GLOBAL_LIB_ROOT. '/gdp_hook.php';
require_once LIB_ROOT_V4 . '/LogManager.class.php';
require_once LIB_ROOT_V4. '/SaveLogManager.class.php';

try{
	LogManager::enter();	
}catch (Exception $e){
	LogManager::leave("error",$e->getMessage());die();
}

try{
	$msg=SaveLogManager::saveLog();
}catch (Exception $e){
	LogManager::leave("error",$e->getMessage());die();
}

LogManager::leave(XA_LOG_RETURN_TYPE,$msg);