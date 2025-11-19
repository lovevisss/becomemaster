<?php

// 如果SESSION没有开启 则开启
if (!isset($_SESSION)) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

require_once '../Config.php';

require_once '../com/supwisdom/institute/authx/demo/cas/common/utils/CasUtil.php';


$casServerUrl = CAS_SERVER_URL;


$isAlive = false;

if ( isset($_SESSION['casUser']) ) {
  $casUser = unserialize($_SESSION['casUser']);
  $isAlive = CasUtil::userOnlineDetect($casServerUrl, $casUser);
}

if ($isAlive) {
  echo "{\"code\": 0, \"message\": null, \"data\": {\"isAlive\": true}}";
} else {
  echo "{\"code\": 0, \"message\": null, \"data\": {\"isAlive\": false}}";
}

?>
