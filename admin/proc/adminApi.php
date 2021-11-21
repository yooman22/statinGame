<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("../../conf/config.php");
require_once("../../conf/dbconn.php");
require_once("../../class/class.mpark.php");
require_once("../../class/class.mparkuser.php");
require_once("../../class/class.mparkstep.php");


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
