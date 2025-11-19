<?php

// 如果SESSION没有开启 则开启
if (!isset($_SESSION)) {
    session_start();
}

require_once '../Config.php';

require_once '../com/supwisdom/institute/authx/demo/cas/common/utils/CasUtil.php';

function startsWith($str, $needle){
  return substr($str, 0, strlen($needle)) === $needle;
}


$casServerUrl = CAS_SERVER_URL;
$appServerUrl = APP_SERVER_URL;


$service = $appServerUrl . "/sso/logout.php";

$returnUrl = $_REQUEST['returnUrl'];
if (!empty($returnUrl)) {
  $service = $service . '?returnUrl='.urlencode(mb_convert_encoding($returnUrl, "utf-8"));
}

$isLogout = CasUtil::logout($casServerUrl, $service, $_REQUEST);

if (!$isLogout) {
  return;
}


session_unset();
session_destroy();


if (!startsWith($returnUrl, "http://") && !startsWith($returnUrl, "https://")) {
  $returnUrl = $appServerUrl . $returnUrl;
}
header('Location: ' . $returnUrl);


?>