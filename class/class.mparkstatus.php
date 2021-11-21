<?php
class Mparkstatus {

  public $status_uid = 0;
  public $status_code = '';
  public $logable_level = 0;
  public $reg_date = '';
  public $content = '';
  public $step_uid = 0;

  function __construct( $data = null ){
    
    if( isset($data) ){
      $this->setFromColumn( $data );
    }
  }

  function setFromColumn( $data ){
    $this->status_uid = $data['f_status_uid'];
    $this->status_code = $data['f_status_code'];
    $this->logable_level = $data['f_logable_level'];
    $this->reg_date = $data['f_reg_date'];
    $this->content = $data['f_content'] != null ? $data['f_content'] : null;
    $this->step_uid = $data['f_step_uid'] != null ? $data['f_step_uid'] : null;
  }

  function setFromJson( $json_data ){

    if(isset($json_data['status_uid'])){
      $this->status_uid = $json_data['status_uid'];
    }
    if(isset($json_data['status_code'])){
      $this->status_code = $json_data['status_code'];
    }
    if(isset($json_data['logable_level'])){
      $this->logable_level = $json_data['logable_level'];
    }
    if(isset($json_data['reg_date'])){
      $this->reg_date = $json_data['reg_date'];
    }
    if(isset($json_data['content'])){
      $this->content = $json_data['content'];
    }
    if(isset($json_data['step_uid'])){
      $this->step_uid = $json_data['step_uid'];
    }
  }

  function getJson(){
    $json = '{';
    $json .= '"status_uid":'.$this->status_uid;
    $json .= ',"status_code":"'.$this->status_code.'"';
    $json .= ',"logable_level":'.$this->logable_level;
    $json .= ',"reg_date":"'.$this->reg_date.'"';
    $json .= ',"content":"'.$this->content.'"';
    $json .= ',"step_uid":"'.$this->step_uid.'"';
    $json .= '}';
    return $json;
  }

}
?>
