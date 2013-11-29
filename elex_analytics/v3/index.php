<?php

/*analytic.337.com/v3/index.php?appid=XX&&log={xxx}
 * 
 * or
 * 
 *analytic.337.com/v3/index.php?appid=XX&&log={xxx}
 * 
 *save base log
 *set the XA_LOG_RETURN_TYPE
 *and run the index.php
 */
define('XA_LOG_RETURN_TYPE', "php");
include_once '../config.inc.php';
require_once ROOT_V3.'/main.php';
