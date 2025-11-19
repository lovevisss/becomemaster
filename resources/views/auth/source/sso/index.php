<?php

// 如果SESSION没有开启 则开启
if (!isset($_SESSION)) {
    session_start();
}

require_once 'Config.php';

require_once 'com/supwisdom/institute/authx/demo/cas/common/utils/CasUtil.php';

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
  <meta content="text/html;charset=UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>CAS 认证对接示例</title>

  <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />

</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <!-- Navbar content -->

    <div class="container">

    <a class="navbar-brand" href="{% url 'home' %}">示例</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="{% url 'web_index' %}">首页</a>
        </li>
      </ul>
    </div>
    
    </div>
    
  </nav>

  <div style="height: 50px;"></div>

<div class="container">

  <article class="markdown-body">

    <h1>示例</h1>
    
    <h2>对接文档</h2>

    <a href="https://authx-service.dev2.supwisdom.com/docs/authx/index.html#/guide/cas/st/guide" target="_blank">https://authx-service.dev2.supwisdom.com/docs/authx/index.html#/guide/cas/st/guide</a>

    <div style="height: 20px;"></div>


    <h2>示例源码</h2>

    <a href="demo/authx-demo-cas-php.zip" target="_blank">authx-demo-cas-php.zip</a>

    <div style="height: 20px;"></div>


    <h2>登录操作</h2>

    <div>登录：<a href="sso/login.php?returnUrl=/index.php">CAS Login</a></div>

    <div>注销：<a href="sso/logout.php?returnUrl=/index.php">CAS Logout</a></div>


    <div>检测登录状态：<a href="#" id="userOnlineDetectButton" >CAS User Online Detect</a>  <label id="userOnlineDetectLabel">&lt;== 点击获取登录状态</label> </div>


    <div>单点注销接口：<a href="sso/slo.php?callback=jsonpcallback" target="_blank">单点注销接口</a></div>

    <div style="height: 20px;"></div>


    <h2>登录信息</h2>

    <?php
    if ( isset($_SESSION['casUser']) ) {
      $casUser = unserialize($_SESSION['casUser']);
    ?>
      
    <div>当前 登录用户：
      <span><?php echo $casUser->getUser() ?></span>
    </div>

    <div style="height: 20px;"></div>
    
    <div style="line-height: 30px;">
      <?php foreach ( $casUser->getAttributes() as $k=>$v) { ?>
      <div><span><?php echo $k ?></span>：<span><?php echo $v ?></span></div>
      <?php } ?>
    </div>


    <?php } ?>

    <!-- <div th:unless="${#strings.isEmpty(error)}">
      <span th:utext="${error}">error</span>
    </div> -->
    
  </article>
  
</div>

<script type="text/javascript" src="https://cdn.bootcdn.net/ajax/libs/jquery/3.0.0/jquery.js"></script>
<script type="text/javascript" src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script type="text/javascript">
$(function(){


  // $.ajaxSetup({
  //     data: {csrfmiddlewaretoken: '{{ csrf_token }}' },
  // });

  

  $(document).ready(function(){

    $("#userOnlineDetectButton").click(function(){

      var userOnlineDetectUrl = "sso/userOnlineDetect.php";

      $.post({
        url: userOnlineDetectUrl,
        success: function(data) {
          console.log("data", data);
      
          if (data == null) {
      	
          } else {
            if (data.data.isAlive == false) {
              $("#userOnlineDetectLabel").html("未在线，你可以点击 CAS Login 进行登录");
              // TODO: 你可以跳转处理重新登录的逻辑
              // window.location.href = loginUrl;
            } else {
              $("#userOnlineDetectLabel").html("在线");
            }
          }
        }
      });

      return false;

    });

  });
  
});

</script>

</body>

</html>
