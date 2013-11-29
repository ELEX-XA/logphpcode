<?php
$a["0"]=3;
$a["1"]=2;
settype($a,"object");
var_dump(json_encode($a));
phpinfo();
?>
