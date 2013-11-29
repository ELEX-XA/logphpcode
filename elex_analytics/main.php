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
require_once LIB_ROOT_V2 . '/LogManager.class.php';
require_once LIB_ROOT_V2. '/SaveLogManager.class.php';

try{
	LogManager::enter();	
}catch (Exception $e){
	LogManager::leave(XA_LOG_RETURN_TYPE,$e->getMessage());
}

try{
	if(XA_LOG_SAVE_TYPE=="index"){
		if(isset($_REQUEST['logs']) || isset($_REQUEST['log'])){
			SaveLogManager::saveLog('base_package');
		}
		else if(isset($_REQUEST['json'])){
			SaveLogManager::saveLog('all');
		}
		else{
			SaveLogManager::saveLog('base');
		}	
	}
	else if(XA_LOG_SAVE_TYPE=="storelog"){
		SaveLogManager::saveLog('store');
	}
}catch (Exception $e){
	LogManager::leave(XA_LOG_RETURN_TYPE,$e->getMessage());
}

LogManager::leave(XA_LOG_RETURN_TYPE);