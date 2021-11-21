<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
class Mpark {

  public $dbconn = null;

  function __construct( $dbconn ){
    $this->dbconn = $dbconn;
  }

  //아이디 중복확인
  function searchUser( $json_data ){
    global $tables;
    $user = new Mparkuser();
    $user->setFromJson( $json_data );
    $sql = "SELECT * FROM ".$tables['user']." WHERE ";
    if( $user->user_id != null && $user->user_id != ''){
      $sql .= "f_user_id = '".$user->user_id."'";
    }else if( $user->user_knick != null && $user->user_knick != ''){
      $sql .= "f_user_knick = '".$user->user_knick."'";
    }else if( $user->user_email != null && $user->user_email != ''){
      $sql .= "f_user_email = '".$user->user_email."'";
    }else{
      $sql .= " 1=0 ";
    }

    $list = array();
    $query = mysqli_query($this->dbconn, $sql);
    while( $data = mysqli_fetch_array( $query,MYSQLI_ASSOC )){
      array_push($list,$data);
    }
    return $list;
  }

  //회원가입
  function insertUser( $json_data ){
    global $tables;
    $user = new Mparkuser();
    $user->setFromJson( $json_data );
    $user->enable_yn = "Y";
    $user->cert_yn = "Y";
    $user->terms_yn = "Y";
    $sql = "INSERT INTO ".$tables['user']." (f_user_name
                                              , f_user_id
                                              , f_user_pwd
                                              , f_reg_date
                                              , f_modify_date
                                              , f_enable_yn
                                              , f_user_knick
                                              , f_user_phone
                                              , f_user_hospital
                                              , f_user_email
                                              , f_user_depart
                                              , f_cert_yn
                                              , f_terms_yn
                                              , f_terms_agree_date
                                              , f_user_level
                                              , f_user_area
                                              , f_user_cscode
                                              , f_user_mrname) VALUES ( ";
    $sql .= $this->checkValue($user->user_name);
    $sql .= ",".$this->checkValue($user->user_id);
    $sql .= ",PASSWORD(".$this->checkValue(trim($user->user_pwd)).")";
    $sql .= ",now()";
    $sql .= ",now()";
    $sql .= ",".$this->checkValue($user->enable_yn);
    $sql .= ",".$this->checkValue($user->user_knick);
    $sql .= ",".$this->checkValue($user->user_phone);
    $sql .= ",".$this->checkValue($user->user_hospital);
    $sql .= ",".$this->checkValue($user->user_email);
    $sql .= ",".$this->checkValue($user->user_depart);
    $sql .= ",".$this->checkValue($user->cert_yn);
    $sql .= ",".$this->checkValue($user->terms_yn);
    $sql .= ",now()";
    $sql .= ",".$this->checkValue($user->user_level);
    $sql .= ",".$this->checkValue($user->user_area);
    $sql .= ",".$this->checkValue($user->user_cscode);
    $sql .= ",".$this->checkValue($user->user_mrname);
    $sql .= ")";
    $query = mysqli_query($this->dbconn, $sql);

    return $query;
  }

  //비밀번호 찾기시 아이디,mrname으로 존재 여부 확인
  function checkUser2( $json_data ){
    global $tables;
    $user = new Mparkuser();
    $user->setFromJson( $json_data );
    $sql = "SELECT COUNT(*) AS CNT FROM ".$tables['user']." WHERE ";
    $sql .= "f_user_id = '".$user->user_id."'";
    $sql .= " and f_user_mrname = '".$user->user_mrname."'";

    $query = mysqli_query($this->dbconn, $sql);
    $data = mysqli_fetch_array( $query,MYSQLI_ASSOC );
    return $data['CNT'];
  }

  //비밀번호 변경
  function updateUser2( $json_data ){
    global $tables;
    $user = new Mparkuser();
    $user->setFromJson( $json_data );
    $sql = "UPDATE ".$tables['user']." SET f_user_pwd = PASSWORD(".$this->checkValue($user->user_pwd)."), f_modify_date = now() WHERE f_user_id = ".$this->checkValue($user->user_id);
    $query = mysqli_query($this->dbconn, $sql);
    return $query;
  }


  //로그인
  function getUserLogin( $user_id, $user_pwd ){
    global $tables;
    $list = array();
    $sql = "SELECT * FROM ".$tables['user']." WHERE  f_user_id = ".$this->checkValue($user_id)." AND f_user_pwd = PASSWORD(".$this->checkValue($user_pwd).") LIMIT 1";
    $query = mysqli_query($this->dbconn, $sql );
    $data = mysqli_fetch_array( $query, MYSQLI_ASSOC );
    if( $data == null ){
      return null;
    }else{
      return new Mparkuser($data);
    }
  }





  function getReadyStatus( $status_code ){
    global $tables;
    $sql = "SELECT * FROM ".$tables['status']." WHERE f_status_code = '".$status_code."'";
    $query = mysqli_query($this->dbconn, $sql);
    $data = mysqli_fetch_array( $query,MYSQLI_ASSOC );
    return new Mparkstatus( $data );
  }

  function getOnairStep(){
    global $tables;
    $sql = "SELECT * FROM ".$tables['step']." WHERE f_step_status = 1 ORDER BY f_step_uid DESC LIMIT 1";
    $query = mysqli_query($this->dbconn, $sql);
    $data = mysqli_fetch_array( $query,MYSQLI_ASSOC );
    return $data;
  }


////////////////////////////////////////////////////////////////
function insertStep( $json_data ){
  global $tables;
  $dto = new Mparkstep( null );
  $dto->setFromJson( $json_data );
  $sql = "INSERT INTO ".$tables['step']." (f_step_title,f_step_video,f_reg_date,f_step_date,f_step_status,f_step_wincount )";
  $sql .= " VALUES (";
  $sql .= "'".$dto->step_title."'";
  $sql .= ",'".$dto->step_video."'";
  $sql .= ",now()";
  // $sql .= ",'".$dto->step_date."'"; 이거 뭔지 물어보고 바꿔야함
  $sql .= ",now()";
  $sql .= ",".$dto->step_status;
  $sql .= ",".$dto->step_wincount;
  $sql .= ")";
  $qry = mysqli_query($this->dbconn,$sql) or die("query error : ".$sql);
  return $qry;
}

function updateStep( $json_data ){
  global $tables;
  $dto = new Mparkstep( null );
  $dto->setFromJson( $json_data );
  $sql = "UPDATE ".$tables['step']." SET ";
  $sql .= "f_step_title = '".$dto->step_title."'";
  $sql .= ",f_step_video = '".$dto->step_video."'";;
  $sql .= ",f_step_date = '".$dto->step_date."'";
  $sql .= ",f_step_wincount = ".$dto->step_wincount;
  $sql .= " WHERE f_step_uid = ".$dto->step_uid;
  $qry = mysqli_query($this->dbconn,$sql) or die("query error : ".$sql);
  return $qry;
}

function listStep( $json_data ){
  global $tables;
  $dto = new Mparksearch();
  $dto->setFromJson( $json_data );
  $sql = "SELECT * FROM ".$tables['step']." ORDER BY f_step_status ASC,f_reg_date DESC";
  $list = array();
  $query = mysqli_query($this->dbconn, $sql);
  while( $data = mysqli_fetch_array( $query,MYSQLI_ASSOC )){
    array_push( $list, new Mparkstep($data) );
  }
  return $list;
}

function selectStepedQuizList( $step_uid ){
  global $tables;
  $sql = "SELECT A.*,B.user_count,C.f_view_content,C.f_seq_no FROM ".$tables['quiz']." A ";
  $sql .= " LEFT JOIN ( SELECT f_quiz_uid, COUNT(*) AS user_count FROM ".$tables['answer']." WHERE f_step_uid = ".$step_uid." GROUP BY f_quiz_uid ) B ON B.f_quiz_uid = A.f_quiz_uid ";
  $sql .= " LEFT JOIN ".$tables['quiz_view']." C ON ( C.f_quiz_uid = A.f_quiz_uid AND C.f_view_uid = A.f_correct_uid )";
  $sql .= " WHERE A.f_step_uid = ".$step_uid." ";
  $sql .= " ORDER BY A.f_show_num ASC";
  $list = array();
  $query = mysqli_query($this->dbconn, $sql);
  while( $data = mysqli_fetch_array( $query,MYSQLI_ASSOC )){
    $quiz = new Mparkquiz( null, null );
    $quiz->setFromColumn( $data );
    array_push($list,$quiz);
  }
  return $list;
}

function selectStep( $uid ){
  global $tables;
  if( isset($uid) && $uid >= 0){
    $sql = "SELECT * FROM ".$tables['step']." WHERE f_step_uid = ".$uid;
    $query = mysqli_query($this->dbconn, $sql);
    $data = mysqli_fetch_array( $query,MYSQLI_ASSOC );
    $step = new Mparkstep( null );
    $step->setFromColumn($data);
    return $step;
  }
}

function deleteStep( $step_uid ){
  global $tables;
  $sql = "DELETE FROM ".$tables['step']." WHERE f_step_uid = ".$step_uid;
  mysqli_query($this->dbconn, $sql);

  $sql = "UPDATE ".$tables['quiz']." SET f_step_uid = 0 WHERE f_step_uid = ".$step_uid;
  $query = mysqli_query($this->dbconn, $sql);
  return $query;
}
////////////////////////////////////////////////////////////////















  function checkValue( $v ){
    if( is_string($v) ){
      return "'".strip_tags( stripslashes($v) )."'";
    }else{
      return $v;
    }
  }


}
?>
