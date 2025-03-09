<?php

class HttpUtil {

  public static function get($url) {
  
    $ch = curl_init();
  
    curl_setopt($ch, CURLOPT_URL, $url);

    // 不返回HTTP头部信息
    curl_setopt($ch, CURLOPT_HEADER, false);
    // 设置获取的信息以文件流的形式返回
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
    // 跳过证书检查
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // 不从证书中检查SSL加密算法是否存在
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  
    // 执行命令
    $data = curl_exec($ch);
  
    // 关闭URL请求
    curl_close($ch);

    // 显示获得的数据
    // print_r($data);

    return $data;
  }

  public static function post($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    // 设置请求方式为post
    curl_setopt($ch, CURLOPT_POST, true);

    // 执行后不直接打印出来
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // 请求头，可以传数组
    curl_setopt($ch, CURLOPT_HEADER, false);

    // post的变量
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    // curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(array('Expect:'), empty($header) ? array() : $header));//header头参数

    // 跳过证书检查
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // 不从证书中检查SSL加密算法是否存在
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    // 执行命令
    $data = curl_exec($ch);

    // 关闭URL请求
    curl_close($ch);

    // 显示获得的数据
    // print_r($data);

    return $data;
  }

}

?>
