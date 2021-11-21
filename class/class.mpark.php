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

  //라이브 정보를 가져온다.
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



  function checkValue( $v ){
    if( is_string($v) ){
      return "'".strip_tags( stripslashes($v) )."'";
    }else{
      return $v;
    }
  }


}
?>
