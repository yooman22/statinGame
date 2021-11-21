<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("../../conf/config.php");
require_once("../../conf/dbconn.php");
require_once("../../class/class.mpark.php");
require_once("../../class/class.mparkuser.php");


$mpark = new Mpark( $dbconn );
$json_data = json_decode(file_get_contents('php://input'), true);

$result = array();

if(isset( $json_data['type']) && isset( $json_data['mode'] ) ){
  $_type = $json_data['type'];
  $_mode = $json_data['mode'];


  if( $_type == 'user'){
    switch($_mode){
      case 'insert':
          $result['count'] = $mpark->insertUser( $json_data );
          echo printJson('등록성공',$result);
        break;
      case 'update':
        if( $result['count'] = $mpark->updateUser( $json_data ) ){
          echo printJson('수정성공',$result);
        }else{
          echo printJson('수정실패',null);
        }
        break;
      case 'find':
        $check_cnt = $mpark->checkUser2( $json_data );
        if( $check_cnt > 0 ){
		  $mpark->updateUser2( $json_data );
          echo printJson('비밀번호가 정상적으로 변경되었습니다.',$result);
        }else{
          echo printJson('등록된 회원정보가 없습니다.',null);
        }
        break;
      case 'search_id':
        if( !isset($json_data['user_id']) ){
          echo printJson('아이디를 입력해주세요.');
        }else if( $json_data['user_id'] == '' ){
          echo printJson('아이디를 입력해주세요.');
        }else{
          $user_list = $mpark->searchUser( $json_data );
          if( $user_list == null ){
            $result['count'] = 0;
            echo printJson('',$result);
          }else{
            $result = array();
            $result['count'] = sizeof( $user_list );
            echo printJson('',$result);
          }
        }
        break;
      case 'search_knick':
        if( !isset($json_data['user_knick']) ){
          echo printJson('별명을 입력해주세요.');
        }else{
          $user_list = $mpark->searchUser( $json_data );
          if( $user_list == null ){
            $result['count'] = 0;
            echo printJson('',$result);
          }else{
            $result['count'] = sizeof( $user_list );
            echo printJson('',$result);
          }
        }
        break;
      case 'view':
        $user = new Mparkuser( null );
        $user->setFromJson($json_data);
        $result_user = $mpark->getUserInfo( $user->user_uid );
        echo $result_user->getJson();
        break;
      case 'delete':
        $user = new Mparkuser( null );
        $user->setFromJson($json_data);
        if( $mpark->deleteUser($user->user_uid) ){
          echo printJson( '삭제했습니다.', null);
        }else{
          echo printJson( '삭제실패', null);
        }
        break;
    }
  }

}else{
  echo printJson( '잘못된 접근입니다.', null);
}


//var_dump( $json_data );
//var_dump( printJson($json_data) );
//echo $json_data['step_title'];


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
