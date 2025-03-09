<?php

require_once dirname(__FILE__) . '/HttpUtil.php';

class CasUser {


    /**
     * 用户获取 CasUser 的 service
     */
    private $_service;

    /**
     * 用户获取 CasUser 的 票据 ticket
     */
    private $_ticket;

    /**
     * 账号名
     */
    private $_user = '';

    /**
     * 账号属性
     *
attribute | 说明 | 示例
- | - | -
name | 姓名 | 智慧校园管理员
accountId | 帐号ID | 1
userId | 用户ID | 1
userName | 用户姓名 | 智慧校园管理员
identityTypeId | 身份ID | 1
identityTypeCode | 身份代码 | admin
identityTypeName | 身份名称 | 管理
organizationId | 组织机构ID | 1
organizationCode | 组织机构代码 | 1
organizationName | 组织机构名称 | 智慧大学
     */
    private $_attributes = array();


    public function isEmpty() {
        return empty($this->_user);
    }

    public function setService($service)
    {
        $this->_service = $service;
    }

    public function getService()
    {
    	  return $this->_service;
    }

    public function setTicket($ticket)
    {
        $this->_ticket = $ticket;
    }

    public function getTicket()
    {
    	  return $this->_ticket;
    }

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function getUser()
    {
    	  return $this->_user;
    }

    public function setAttributes($attributes)
    {
        $this->_attributes = $attributes;
    }

    public function getAttributes()
    {
        return $this->_attributes;
    }


    public static function getEmptyCasUser() {
        return new CasUser();
    }

}


class CasUtil {

  /**
   * CAS 登录
   * 1. 判断 request 是否存在 ticket
   * 2. 若不存在，跳转到 CAS 认证的登录页面 /login，并返回 null
   * 3. 若存在，则验证 ticket 是否合法 /serviceValidate
   * 4. 验证成功，返回 CasUser；否则，验证失败，返回 CasUser.EMPTY
   *
   * @param casServerHostUrl
   * @param service
   * @param request
   * @return
   * @throws IOException
   */
  public static function login($casServerHostUrl, $service, $requestParams) {

    $ticket = isset($_REQUEST["ticket"]) ? $_REQUEST["ticket"] : null;
//    dd($_REQUEST);
//    dd(\Illuminate\Support\Facades\Request::all());
      $redirect = $casServerHostUrl ."/login?service=".urlencode(mb_convert_encoding($service, "utf-8"));
    if ( empty($ticket) ) {
      $redirect = $casServerHostUrl ."/login?service=".urlencode(mb_convert_encoding($service, "utf-8"));
//      dd($redirect);

//      header('Location: ' . $redirect);
      return redirect($redirect);
    }
  return redirect($redirect);
    $casUser = CasUtil::serviceValidate($casServerHostUrl, $service, $ticket);
//    dd($casUser);
    return $casUser;
  }


  /**
   * CAS 注销
   * 1. 判断 request 是否存在 logout
   * 2. 若不存在，跳转到 CAS 认证的注销页面 /logout，并返回 false
   * 3. 若存在，则说明 CAS 认证 已注销成功，返回 true
   *
   * @param casServerHostUrl
   * @param service
   * @param request
   * @return
   * @throws IOException
   */
  public static function logout($casServerHostUrl, $service, $requestParams) {

    $logout = $requestParams["logout"];
    if ( empty($logout) ) {
      if ( strpos($service, "?") < 0 ) {
        $service = $service . "?";
      } else {
        $service = $service . "&";
      }
      $service = $service . "logout=logout";

      $redirect = $casServerHostUrl ."/logout?service=".urlencode(mb_convert_encoding($service, "utf-8"));
      header('Location: ' . $redirect);
      return false;
    }

    return true;
  }


  public static function serviceValidate($casServerHostUrl, $service, $ticket) {
    // echo 'serviceValidate';

    $serviceValidateUrl = $casServerHostUrl . "/serviceValidate" ."?service=".urlencode(mb_convert_encoding($service, "utf-8")) ."&ticket=".urlencode($ticket);

    $responseXml = HttpUtil::get($serviceValidateUrl);
    // echo $responseXml;

    // create new DOMDocument object
    $dom = new DOMDocument();
    // Fix possible whitspace problems
    $dom->preserveWhiteSpace = false;
    // CAS servers should only return data in utf-8
    $dom->encoding = "utf-8";
    // read the response of the CAS server into a DOMDocument object
    if ( !($dom->loadXML($responseXml))) {
        // read failed
        // throw new CAS_AuthenticationException(
        //     $this, 'Ticket not validated', $validate_url,
        //     false/*$no_response*/, true/*$bad_response*/, $text_response
        // );
        // $result = false;
        return CasUser::getEmptyCasUser();
    } else if ( !($tree_response = $dom->documentElement) ) {
        // read the root node of the XML tree
        // read failed
        // throw new CAS_AuthenticationException(
        //     $this, 'Ticket not validated', $validate_url,
        //     false/*$no_response*/, true/*$bad_response*/, $text_response
        // );
        // $result = false;
        return CasUser::getEmptyCasUser();
    } else if ($tree_response->localName != 'serviceResponse') {
        // insure that tag name is 'serviceResponse'
        // bad root node
        // throw new CAS_AuthenticationException(
        //     $this, 'Ticket not validated', $validate_url,
        //     false/*$no_response*/, true/*$bad_response*/, $text_response
        // );
        // $result = false;
        return CasUser::getEmptyCasUser();
    } else if ($tree_response->getElementsByTagName("authenticationSuccess")->length != 0) {
        // authentication succeded, extract the user name
        $success_elements = $tree_response
            ->getElementsByTagName("authenticationSuccess");
        if ( $success_elements->item(0)->getElementsByTagName("user")->length == 0) {
            // no user specified => error
            // throw new CAS_AuthenticationException(
            //     $this, 'Ticket not validated', $validate_url,
            //     false/*$no_response*/, true/*$bad_response*/, $text_response
            // );
            // $result = false;
            return CasUser::getEmptyCasUser();
        } else {

            $casUser = new CasUser();
            $casUser->setService($service);
            $casUser->setTicket($ticket);

            $casUser->setUser(
                trim(
                    $success_elements->item(0)->getElementsByTagName("user")->item(0)->nodeValue
                )
            );

            $attributes = CasUtil::_readExtraAttributesCas20($success_elements);

            $casUser->setAttributes($attributes);

            return $casUser;
        }
    } else if ( $tree_response->getElementsByTagName("authenticationFailure")->length != 0) {
        // authentication succeded, extract the error code and message
        $auth_fail_list = $tree_response
            ->getElementsByTagName("authenticationFailure");
        // throw new CAS_AuthenticationException(
        //     $this, 'Ticket not validated', $validate_url,
        //     false/*$no_response*/, false/*$bad_response*/,
        //     $text_response,
        //     $auth_fail_list->item(0)->getAttribute('code')/*$err_code*/,
        //     trim($auth_fail_list->item(0)->nodeValue)/*$err_msg*/
        // );
        // $result = false;
        return CasUser::getEmptyCasUser();
    } else {
        // throw new CAS_AuthenticationException(
        //     $this, 'Ticket not validated', $validate_url,
        //     false/*$no_response*/, true/*$bad_response*/,
        //     $text_response
        // );
        // $result = false;
        return CasUser::getEmptyCasUser();
    }

  }



  /**
   * This method will parse the DOM and pull out the attributes from the XML
   * payload and put them into an array, then put the array into the session.
   *
   * @param string $success_elements payload of the response
   *
   * @return bool true when successfull, halt otherwise by calling
   * CAS_Client::_authError().
   */
  private static function _readExtraAttributesCas20($success_elements)
  {
      $extra_attributes = array();

      // "Jasig Style" Attributes:
      //
      // 	<cas:serviceResponse xmlns:cas='http://www.yale.edu/tp/cas'>
      // 		<cas:authenticationSuccess>
      // 			<cas:user>jsmith</cas:user>
      // 			<cas:attributes>
      // 				<cas:attraStyle>RubyCAS</cas:attraStyle>
      // 				<cas:surname>Smith</cas:surname>
      // 				<cas:givenName>John</cas:givenName>
      // 				<cas:memberOf>CN=Staff,OU=Groups,DC=example,DC=edu</cas:memberOf>
      // 				<cas:memberOf>CN=Spanish Department,OU=Departments,OU=Groups,DC=example,DC=edu</cas:memberOf>
      // 			</cas:attributes>
      // 			<cas:proxyGrantingTicket>PGTIOU-84678-8a9d2sfa23casd</cas:proxyGrantingTicket>
      // 		</cas:authenticationSuccess>
      // 	</cas:serviceResponse>
      //
      if ( $success_elements->item(0)->getElementsByTagName("attributes")->length != 0) {
          $attr_nodes = $success_elements->item(0)
              ->getElementsByTagName("attributes");
          // phpCas :: trace("Found nested jasig style attributes");
          if ($attr_nodes->item(0)->hasChildNodes()) {
              // Nested Attributes
              foreach ($attr_nodes->item(0)->childNodes as $attr_child) {
                  // phpCas :: trace(
                  //     "Attribute [".$attr_child->localName."] = "
                  //     .$attr_child->nodeValue
                  // );
                  CasUtil::_addAttributeToArray(
                      $extra_attributes, $attr_child->localName,
                      $attr_child->nodeValue
                  );
              }
          }
      }
      // } else {
      //     // "RubyCAS Style" attributes
      //     //
      //     //   <cas:serviceResponse xmlns:cas='http://www.yale.edu/tp/cas'>
      //     //     <cas:authenticationSuccess>
      //     //       <cas:user>jsmith</cas:user>
      //     //
      //     //       <cas:attraStyle>RubyCAS</cas:attraStyle>
      //     //       <cas:surname>Smith</cas:surname>
      //     //       <cas:givenName>John</cas:givenName>
      //     //       <cas:memberOf>CN=Staff,OU=Groups,DC=example,DC=edu</cas:memberOf>
      //     //       <cas:memberOf>CN=Spanish Department,OU=Departments,OU=Groups,DC=example,DC=edu</cas:memberOf>
      //     //
      //     //       <cas:proxyGrantingTicket>PGTIOU-84678-8a9d2sfa23casd</cas:proxyGrantingTicket>
      //     //     </cas:authenticationSuccess>
      //     //   </cas:serviceResponse>
      //     //
      //     phpCas :: trace("Testing for rubycas style attributes");
      //     $childnodes = $success_elements->item(0)->childNodes;
      //     foreach ($childnodes as $attr_node) {
      //         switch ($attr_node->localName) {
      //         case 'user':
      //         case 'proxies':
      //         case 'proxyGrantingTicket':
      //             continue;
      //         default:
      //             if (strlen(trim($attr_node->nodeValue))) {
      //                 phpCas :: trace(
      //                     "Attribute [".$attr_node->localName."] = ".$attr_node->nodeValue
      //                 );
      //                 $this->_addAttributeToArray(
      //                     $extra_attributes, $attr_node->localName,
      //                     $attr_node->nodeValue
      //                 );
      //             }
      //         }
      //     }
      // }

      // "Name-Value" attributes.
      //
      // Attribute format from these mailing list thread:
      // http://jasig.275507.n4.nabble.com/CAS-attributes-and-how-they-appear-in-the-CAS-response-td264272.html
      // Note: This is a less widely used format, but in use by at least two institutions.
      //
      // 	<cas:serviceResponse xmlns:cas='http://www.yale.edu/tp/cas'>
      // 		<cas:authenticationSuccess>
      // 			<cas:user>jsmith</cas:user>
      //
      // 			<cas:attribute name='attraStyle' value='Name-Value' />
      // 			<cas:attribute name='surname' value='Smith' />
      // 			<cas:attribute name='givenName' value='John' />
      // 			<cas:attribute name='memberOf' value='CN=Staff,OU=Groups,DC=example,DC=edu' />
      // 			<cas:attribute name='memberOf' value='CN=Spanish Department,OU=Departments,OU=Groups,DC=example,DC=edu' />
      //
      // 			<cas:proxyGrantingTicket>PGTIOU-84678-8a9d2sfa23casd</cas:proxyGrantingTicket>
      // 		</cas:authenticationSuccess>
      // 	</cas:serviceResponse>
      //
      if (!count($extra_attributes)
          && $success_elements->item(0)->getElementsByTagName("attribute")->length != 0
      ) {
          $attr_nodes = $success_elements->item(0)
              ->getElementsByTagName("attribute");
          $firstAttr = $attr_nodes->item(0);
          if (!$firstAttr->hasChildNodes()
              && $firstAttr->hasAttribute('name')
              && $firstAttr->hasAttribute('value')
          ) {
              // phpCas :: trace("Found Name-Value style attributes");
              // Nested Attributes
              foreach ($attr_nodes as $attr_node) {
                  if ($attr_node->hasAttribute('name')
                      && $attr_node->hasAttribute('value')
                  ) {
                      // phpCas :: trace(
                      //     "Attribute [".$attr_node->getAttribute('name')
                      //     ."] = ".$attr_node->getAttribute('value')
                      // );
                      CasUtil::_addAttributeToArray(
                          $extra_attributes, $attr_node->getAttribute('name'),
                          $attr_node->getAttribute('value')
                      );
                  }
              }
          }
      }

      return $extra_attributes;
  }


  /**
   * Add an attribute value to an array of attributes.
   *
   * @param array  &$attributeArray reference to array
   * @param string $name            name of attribute
   * @param string $value           value of attribute
   *
   * @return void
   */
  private static function _addAttributeToArray(array &$attributeArray, $name, $value)
  {
      // If multiple attributes exist, add as an array value
      if (isset($attributeArray[$name])) {
          // Initialize the array with the existing value
          if (!is_array($attributeArray[$name])) {
              $existingValue = $attributeArray[$name];
              $attributeArray[$name] = array($existingValue);
          }

          $attributeArray[$name][] = trim($value);
      } else {
          $attributeArray[$name] = trim($value);
      }
  }




  /**
   * CAS 登录状态检测
   * 依赖的认证版本：1.2.11-SNAPSHOT，1.3.7-SNAPSHOT，1.4.6-SNAPSHOT，1.5.3-SNAPSHOT
   *
   * @param casServerHostUrl
   * @param casUser
   * @return
   * @throws UnsupportedEncodingException
   */
  public static function userOnlineDetect($casServerHostUrl, $casUser) {
    return CasUtil::userOnlineDetect2($casServerHostUrl, $casUser->getService(), $casUser->getTicket(), $casUser->getUser());
  }

  public static function userOnlineDetect2($casServerHostUrl, $service, $ticket, $username) {

    $userOnlineDetectUrl = $casServerHostUrl . "/login/userOnlineDetect"
      ."?service=".urlencode(mb_convert_encoding($service, "utf-8"))
      ."&ticket=".urlencode($ticket)
      ."&username=".urlencode($username);

    $responseJSON = HttpUtil::post($userOnlineDetectUrl);
    // echo $responseJSON;

    /*
登录状态正常
{
  "code":0,
  "data":{
    "isAlive":true
  },
  "message":"已登录"
}
登录状态异常
{
  "code":-1,
  "message":"已过期",
  "error":{
    "error":"已过期"
  }
}
{
  "code":-1,
  "message":"已注销",
  "error":{
    "error":"已注销"
  }
}
     */

    $res = json_decode($responseJSON, true);
    // print_r($res);

    if ( isset($res) && isset($res['data']) ) {
      $isAlive = $res['data']['isAlive'];
      //print_r($isAlive);

      return $isAlive;
    }

    return false;
  }

}

?>
