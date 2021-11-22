<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("../../conf/config.php");
require_once("../../conf/dbconn.php");
require_once("../../class/class.mpark.php");
require_once("../../class/class.mparkuser.php");
require_once("../../class/class.mparkstep.php");
require_once("../../class/class.mparkquiz.php");
require_once("../../class/class.mparkquizview.php");


$mpark = new Mpark( $dbconn );
$json_data = json_decode(file_get_contents('php://input'), true);

$result = array();

if(isset( $json_data['type']) && isset( $json_data['mode'] ) ){
  $_type = $json_data['type'];
  $_mode = $json_data['mode'];


  if($_type == 'step'){
    $step = new Mparkstep( null );
    $step->setFromJson( $json_data );
    switch($_mode){
      case 'insert':
        $mpark->insertStep( $json_data );
        echo printJson('등록성공');
        break;
      case 'add_quiz':
        $mpark->clearQuizStep( $step->step_uid );
        if(isset($json_data['quiz_list'])){
          foreach($json_data['quiz_list'] as $v){
            $quiz = new Mparkquiz(null, null);
            $quiz->setFromJson( $v );
            $mpark->updateQuizStep( $quiz->quiz_uid, $step->step_uid );
          }
        }
        echo printJson('저장성공',null);
        break;
      case 'view':
        $result_step = $mpark->selectStep($step->step_uid);
        $quiz_list = $mpark->selectStepedQuizList($step->step_uid);
        $quiz_json = array();
        foreach($quiz_list as $v ){
          array_push( $quiz_json, $v->getJson(null) );
        }
        echo $result_step->getJson( '['.implode( $quiz_json, ',' ).']' );
        break;
      case 'modify':
        $mpark->updateStep( $json_data );
        foreach($json_data['quiz_list'] as $v){
          $quiz = new Mparkquiz(null,null );
          $quiz->setFromJson( $v );
          $mpark->updateQuizNum( $quiz->quiz_uid, $quiz->show_num );
        }
        echo printJson('저장성공',$result);
        break;
      case 'onair':
        $mpark->changeStepStatus($step->step_uid,1);
        $step_log = new Mparksteplog();
        $step_log->step_uid = $step->step_uid;
        $step_log->step_status = 'onair';
        $mpark->insertStepLog( $step_log );
        echo printJson('방송을 시작합니다.', null);
        break;
      case 'offair':
        $mpark->changeStepStatus($step->step_uid,0);
        $step_log = new Mparksteplog();
        $step_log->step_uid = $step->step_uid;
        $step_log->step_status = 'offair';
        $mpark->insertStepLog( $step_log );
        echo printJson('방송을 종료합니다.', null);
        break;
      case 'delete':
        if(isset($json_data['step_list'])){
          foreach($json_data['step_list'] as $v){
            $mpark->deleteStep( $v['step_uid'] );
          }
        }
        echo printJson('삭제완료',null);
        break;
      case 'winner':
        $result['step_uid'] = $step->step_uid;
        $result['answer_list'] = array();
        $step = $mpark->selectStep($step->step_uid);
        $list = $mpark->listStepWinner( $step->step_uid, $step->step_wincount );
        foreach( $list as $v ){
          array_push( $result['answer_list'], $v->getResultJson());
        }
        echo printJson('',$result);
        break;
      case 'copy':
        $new_step_uid = $mpark->copyStep($step->step_uid);
        $quiz_list = $mpark->selectStepedQuizList($step->step_uid);
        foreach($quiz_list as $v ){
          if( $new_step_uid ){
            $new_quiz_uid = $mpark->copyQuiz($v->quiz_uid,$new_step_uid);
            if( $new_quiz_uid ){
              $view_list = $mpark->listQuizView( $v->quiz_uid );
              foreach($view_list as $qv ){
                $new_view_uid = $mpark->copyQuizView( $qv->view_uid, $new_quiz_uid);
                if( $qv->view_uid == $v->correct_uid ){
                  $mpark->updateQuizCorrect($new_quiz_uid,$new_view_uid);
                }
              }
            }
          }
        }
        echo printJson('복사완료',null);
        break;
      case 'truncate':
        $mpark->truncateStep($step->step_uid);
        echo printJson('초기화완료',null);
        break;
    }
  }else if($_type == 'quiz'){
    $quiz = new Mparkquiz(null,null);
    if(isset($json_data['currect'])){
      $correct_num = $json_data['currect'];
    }
    $quiz->setFromJson($json_data);
    switch($_mode){
      case 'insert':
        $quiz->quiz_uid = $mpark->insertQuiz( $quiz );
        foreach( $json_data['view_list'] as $v ){
          $quiz_view = new MparkquizView( $quiz->quiz_uid );
          $quiz_view->setFromJson( $v );
          $quiz_view_uid = $mpark->insertQuizView( $quiz_view );
          if( $correct_num == $quiz_view->seq_no ){
            $quiz->correct_uid = $quiz_view_uid;
          }
        }
        $mpark->updateQuiz( $quiz );
        echo printJson( '저장했습니다.', null);
        break;
      case 'modify':
        $mpark->deleteAllViw( $quiz->quiz_uid );
        foreach( $json_data['view_list'] as $v ){
          $quiz_view = new MparkquizView( $quiz->quiz_uid );
          $quiz_view->setFromJson( $v );
          $quiz_view_uid = $mpark->insertQuizView( $quiz_view );
          if( $correct_num == $quiz_view->seq_no ){
            $quiz->correct_uid = $quiz_view_uid;
          }
        }
        $mpark->updateQuiz( $quiz );
        echo printJson( '저장했습니다.', null);
        break;
      case 'view':
        $info = $mpark->selectQuiz( $quiz->quiz_uid );
        $list = $mpark->listQuizView( $quiz->quiz_uid );
        $arr = array();
        foreach($list as $v){
          array_push( $arr,$v->getJson() );
        }
        echo $info->getJson( '['.implode($arr,',').']' );
        break;
      case 'delete':
        if(isset($json_data['quiz_list'])){
          foreach($json_data['quiz_list'] as $v){
            $mpark->deleteQuiz( $v['quiz_uid'] );
          }
        }
        echo printJson('삭제완료',null);
        break;
    }
  }else if( $_type == 'quiz_view'){
    $quiz_view = new MparkquizView( null );
    $quiz_view->setFromJson($json_data);
    $mpark->deleteQuizView($quiz_view->view_uid);
    echo printJson( '삭제되었습니다.', null);
  }else if( $_type == 'user'){
    switch($_mode){
      case 'insert':
        $check_cnt = $mpark->checkUser( $json_data );
        if( $check_cnt > 0 ){
          echo printJson('이미 사용중인 아이디/별명/이메일/전화번호 주소입니다.',null);
        }else{
          $result['count'] = $mpark->insertUser( $json_data );
          echo printJson('등록성공',$result);
        }
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
