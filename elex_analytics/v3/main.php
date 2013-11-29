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
require_once LIB_ROOT_V3 . '/LogManager.class.php';
require_once LIB_ROOT_V3. '/SaveLogManager.class.php';

try{
	LogManager::enter();	
}catch (Exception $e){
	if(XA_LOG_RETURN_TYPE=='php') {LogManager::leave("error",$e->getMessage());}
}

try{
	SaveLogManager::saveLog();
}catch (Exception $e){
	if(XA_LOG_RETURN_TYPE=='php') {LogManager::leave("error",$e->getMessage());}
}

LogManager::leave(XA_LOG_RETURN_TYPE);