<?php
class Mparkuser {
  public $user_id = "";
  public $user_name = "";
  public $user_email = "";
  public $user_knick = "";
  public $user_hospital = "";
  public $user_depart = "";
  public $user_pwd = "";
  public $user_phone = "";
  public $cert_yn = "N";
  public $terms_yn = "N";
  public $terms_agree_date = "";
  public $reg_date = "";
  public $modify_date = "";
  public $enable_yn = "N";
  public $user_uid = 0;
  public $user_level = 0;
  public $correct_count = 0;
  public $total_time = 0;
  public $user_session = "";
  public $user_area = "";
  public $user_cscode = "";
  public $user_mrname = "";

  function __construct( $arr = null ){
    if( isset($arr) ){
      $this->setFromColumn( $arr );
    }
  }

  function setFromSession(){
    $this->user_uid = $_SESSION['user_uid'];
    $this->user_id = $_SESSION['user_id'];
    $this->user_name = $_SESSION['user_name'];
    $this->user_email = $_SESSION['user_email'];
    $this->user_knick = $_SESSION['user_knick'];
    $this->user_hospital = $_SESSION['user_hospital'];
    $this->user_depart = $_SESSION['user_depart'];
    $this->user_level = $_SESSION['user_level'];
    // $this->user_session = $_SESSION['user_session'];
    $this->user_cscode = $_SESSION['user_cscode'];
    $this->user_mrname = $_SESSION['user_mrname'];
  }


  function setFromJson( $json_data ){

    if(isset($json_data['user_uid'])){
      $this->user_uid = $json_data['user_uid'];
    }
    if(isset($json_data['user_id'])){
      $this->user_id = $json_data['user_id'];
    }
    if(isset($json_data['user_name'])){
      $this->user_name = $json_data['user_name'];
    }
    if(isset($json_data['user_email'])){
      $this->user_email = $json_data['user_email'];
    }
    if(isset($json_data['user_knick'])){
      $this->user_knick = $json_data['user_knick'];
    }
    if(isset($json_data['user_hospital'])){
      $this->user_hospital = $json_data['user_hospital'];
    }
    if(isset($json_data['user_depart'])){
      $this->user_depart = $json_data['user_depart'];
    }
    if(isset($json_data['user_pwd'])){
      $this->user_pwd = $json_data['user_pwd'];
    }
    if(isset($json_data['user_phone'])){
      $this->user_phone = $json_data['user_phone'];
    }
    if(isset($json_data['cert_yn'])){
      $this->cert_yn = $json_data['cert_yn'];
    }
    if(isset($json_data['terms_yn'])){
      $this->terms_yn = $json_data['terms_yn'];
    }
    if(isset($json_data['terms_agree_date'])){
      $this->terms_agree_date = $json_data['terms_agree_date'];
    }
    if(isset($json_data['reg_date'])){
      $this->reg_date = $json_data['reg_date'];
    }
    if(isset($json_data['modify_date'])){
      $this->modify_date = $json_data['modify_date'];
    }
    if(isset($json_data['enable_yn'])){
      $this->enable_yn = $json_data['enable_yn'];
    }
    if(isset($json_data['user_level'])){
      $this->user_level = $json_data['user_level'];
    }
    if(isset($json_data['correct_count'])){
      $this->correct_count = $json_data['correct_count'];
    }
    if(isset($json_data['total_time'])){
      $this->total_time = $json_data['total_time'];
    }
    if(isset($json_data['user_session'])){
      $this->user_session = $json_data['user_session'];
    }
    if(isset($json_data['user_area'])){
      $this->user_area = $json_data['user_area'];
    }
    if(isset($json_data['user_cscode'])){
      $this->user_cscode = $json_data['user_cscode'];
    }
    if(isset($json_data['user_mrname'])){
      $this->user_mrname = $json_data['user_mrname'];
    }


  }

  function setFromResultColumn( $arr ){

  }

  function setFromColumn( $arr ){
    $this->user_id = $arr['f_user_id'];
    $this->user_name = $arr['f_user_name'];
    $this->user_email = $arr['f_user_email'];
    $this->user_knick = $arr['f_user_knick'];
    $this->user_hospital = $arr['f_user_hospital'];
    $this->user_depart = $arr['f_user_depart'];
    $this->user_pwd = $arr['f_user_pwd'];
    $this->user_phone = $arr['f_user_phone'];
    $this->cert_yn = $arr['f_cert_yn'];
    $this->terms_yn = $arr['f_terms_yn'];
    $this->terms_agree_date = $arr['f_terms_agree_date'];
    $this->reg_date = $arr['f_reg_date'];
    $this->modify_date = $arr['f_modify_date'];
    $this->enable_yn = $arr['f_enable_yn'];
    $this->user_uid = $arr['f_user_uid'];
    $this->user_level = $arr['f_user_level'];
    $this->user_cscode = $arr['f_user_cscode'];
    $this->user_mrname = $arr['f_user_mrname'];

    if(isset($arr['user_session'])){
      $this->user_session = $arr['user_session'];
    }

    if(isset($arr['correct_count'])){
      $this->correct_count = $arr['correct_count'];
    }
    if(isset($arr['total_time'])){
      $this->total_time = $arr['total_time'];
    }
    if(isset($arr['f_user_area'])){
      $this->user_area = $arr['f_user_area'];
    }
  }

  function getJson(){
    $json = '{';
    $json .= '"user_id":"'.$this->user_id.'"';
    $json .= ',"user_name":"'.$this->user_name.'"';
    $json .= ',"user_email":"'.$this->user_email.'"';
    //$json .= ',"user_knick":"'.$this->user_knick.'"';
    $json .= ',"user_knick":"'.$this->user_mrname.'"';
    $json .= ',"user_hospital":"'.$this->user_hospital.'"';
    $json .= ',"user_depart":"'.$this->user_depart.'"';
    $json .= ',"user_phone":"'.$this->user_phone.'"';
    $json .= ',"cert_yn":"'.$this->cert_yn.'"';
    $json .= ',"terms_yn":"'.$this->terms_yn.'"';
    $json .= ',"terms_agree_date":"'.$this->terms_agree_date.'"';
    $json .= ',"reg_date":"'.$this->reg_date.'"';
    $json .= ',"modify_date":"'.$this->modify_date.'"';
    $json .= ',"enable_yn":"'.$this->enable_yn.'"';
    $json .= ',"user_uid":'.$this->user_uid;
    $json .= ',"user_level":'.$this->user_level;
    $json .= ',"correct_count":'.$this->correct_count;
    $json .= ',"total_time":'.$this->total_time;
    $json .= ',"user_session":"'.$this->user_session.'"';
    $json .= ',"user_area":"'.$this->user_area.'"';
    // $json .= ',"user_cscode":"'.$this->user_cscode.'"';
    // $json .= ',"user_mrname":"'.$this->user_mrname.'"';
    $json .= '}';
    return $json;
  }

  function getResultJson(){
    $json = '{';
    $json .= '"user_id":"'.$this->user_id.'"';
    //$json .= ',"user_knick":"'.$this->user_knick.'"';
    $json .= ',"user_knick":"'.$this->user_mrname.'"';
    $json .= ',"user_hospital":"'.$this->user_hospital.'"';
    $json .= ',"user_depart":"'.$this->user_depart.'"';
    $json .= ',"correct_count":'.$this->correct_count;
    $json .= ',"total_time":'.$this->total_time;
    $json .= ',"user_area":"'.$this->user_area.'"';
    // $json .= ',"user_cscode":"'.$this->user_cscode.'"';
    // $json .= ',"user_mrname":"'.$this->user_mrname.'"';
    $json .= '}';
    return $json;
  }

}
?>
