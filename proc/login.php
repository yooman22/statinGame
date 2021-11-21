<?php
session_start();
include_once("../conf/config.php");
include_once("../conf/dbconn.php");
include_once("../include/function.php");
include_once("../class/class.mpark.php");
include_once("../class/class.mparkuser.php");

$mpark = new Mpark( $dbconn );
$json_data = json_decode(file_get_contents('php://input'), true);
$user = new Mparkuser(null);
$user->setFromJson( $json_data );
// $ready_status = $mpark->getReadyStatus('live_ready');

//로그인한 회원 정보 $logged_user = mparkuser
$logged_user = $mpark->getUserLogin( $user->user_id, $user->user_pwd );

if($logged_user == null){
  echo printJson('사용자 아이디와 비밀번호를 확인 후 다시 시도해 주세요.', null);
}else{

    $_SESSION['user_id'] = $logged_user->user_id;
    $_SESSION['user_uid'] = $logged_user->user_uid;
    $_SESSION['user_email'] = $logged_user->user_email;
    $_SESSION['user_knick'] = $logged_user->user_knick;
    $_SESSION['user_level'] = $logged_user->user_level;
    $_SESSION['user_name'] = $logged_user->user_name;
    $_SESSION['user_hospital'] = $logged_user->user_hospital;
    $_SESSION['user_depart'] = $logged_user->user_depart;
    $_SESSION['user_cscode'] = $logged_user->user_cscode;
    $_SESSION['user_mrname'] = $logged_user->user_mrname;
    
    echo printJson('success', null);

  }


  function printJson( $message = null, $arr = null ){
    $result_arr = array();
    $result_arr[] = '"message":"'.$message.'"';
    if( $arr ) {
      foreach( $arr as $key => $value ){
        if( is_string($value) ){
          $result_arr[] = '"'.$key.'":"'.$value.'"';
        }else if( is_array($value)){
          $result_arr[] = '"'.$key.'":['.implode($value,',').']';
        }else{
          $result_arr[] = '"'.$key.'":'.$value.'';
        }
      }
    }
    return '{'.implode($result_arr,',').'}';
  }

?>
