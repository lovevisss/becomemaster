<?php

// 如果SESSION没有开启 则开启
if (!isset($_SESSION)) {
    session_start();
}

//require_once 'views/auth/source/Config.php';

//require_once '../com/supwisdom/institute/authx/demo/cas/common/utils/CasUtil.php';

function startsWith($str, $needle){
  return substr($str, 0, strlen($needle)) === $needle;
}


$casServerUrl = CAS_SERVER_URL;
$appServerUrl = APP_SERVER_URL;


$service = $appServerUrl ;
//dd($_REQUEST);
//$returnUrl = $_REQUEST['returnUrl'];
$returnUrl = "http://project.zufedfc.edu.cn/projects";
//if (!empty($returnUrl)) {
//  $service = $service . '?returnUrl='.urlencode(mb_convert_encoding($returnUrl, "utf-8"));
//}


//dd($service);

 CasUtil::login($casServerUrl, $service, $_REQUEST);

//if (empty($casUser)) {
//  return;
//}
//if ($casUser->isEmpty()) {
//  return;
//}
//// print_r( $casUser );
//
//$_SESSION["casUser"] = serialize($casUser);
//
//
//if (!startsWith($returnUrl, "http://") && !startsWith($returnUrl, "https://")) {
//  $returnUrl = $appServerUrl . $returnUrl;
//}
header('Location: ' . $returnUrl);


?>
