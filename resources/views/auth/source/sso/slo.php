<?php

// 如果SESSION没有开启 则开启
if (!isset($_SESSION)) {
    session_start();
}


$callback = $_REQUEST['callback'];


session_unset();
session_destroy();


if ( !empty($callback) ) {
  header('Content-Type: application/javascript; charset=utf-8');
  echo $callback."({\"code\": 0, \"message\": null, \"data\": {\"success\": \"注销成功\"}});";
} else {
  header('Content-Type: application/json; charset=utf-8');
  echo "{\"code\": 0, \"message\": null, \"data\": {\"success\": \"注销成功\"}}";
}

?>
