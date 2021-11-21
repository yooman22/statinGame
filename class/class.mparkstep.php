<?php
class Mparkstep {
  public $step_title = "";
  public $step_video = "";
  public $step_date = "";
  public $step_uid = 0;
  public $step_status = 0;
  public $step_wincount = 14;

  function __construct( $data = null ){
    if( isset($data) ){
      $this->setFromColumn( $data );
    }
  }

  function setFromJson( $json_data ){
    if(isset($json_data['step_uid'])){
      $this->step_uid = $json_data['step_uid'];
    }
    if(isset($json_data['step_title'])){
      $this->step_title = $json_data['step_title'];
    }
    if(isset($json_data['step_video'])){
      $this->step_video = $json_data['step_video'];
    }
    if(isset($json_data['step_date'])){
      $this->step_date = $json_data['step_date'];
    }
    if(isset($json_data['step_status'])){
      $this->step_status = $json_data['step_status'];
    }
    if(isset($json_data['step_wincount'])){
      $this->step_wincount = $json_data['step_wincount'];
    }
  }

  function setFromColumn( $data ){
    if(isset($data['f_step_uid'])){
      $this->step_uid = $data['f_step_uid'];
    }
    if(isset($data['f_step_title'])){
      $this->step_title = $data['f_step_title'];
    }
    if(isset($data['f_step_video'])){
      $this->step_video = $data['f_step_video'];
    }
    if(isset($data['f_step_date'])){
      $this->step_date = $data['f_step_date'];
    }
    if(isset($data['f_step_status'])){
      $this->step_status = $data['f_step_status'];
    }
    if(isset($data['f_step_wincount'])){
      $this->step_wincount = $data['f_step_wincount'];
    }
  }

  public function getJson( $quiz_list ){
    $json = '{';
    $json .= '"step_title":"'.addslashes($this->step_title).'"';
    $json .= ',"step_video":"'.$this->step_video.'"';
    $json .= ',"step_date":"'.$this->step_date.'"';
    $json .= ',"step_uid":'.$this->step_uid;
    $json .= ',"step_status":'.$this->step_status;
    $json .= ',"step_wincount":'.$this->step_wincount;
    if( isset($quiz_list) ){
      $json .= ',"quiz_list":'.$quiz_list;
    }
    $json .= '}';
    return $json;
  }
}
?>
