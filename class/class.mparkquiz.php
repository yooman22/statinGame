<?php
class Mparkquiz {
  public $quiz_uid = 0;
  public $quest = '';
  public $reg_date = '';
  public $user_uid = 0;
  public $wait_count = 0;
  public $correct_uid = 0;
  public $show_num = 0;
  public $winner_count = 0;
  public $step_uid = 0;
  public $user_count = 0;
  public $correct_view_content = "";
  public $correct_view_seq = 0;

  function __construct( $quest = null, $wait_count = null ){
    if(isset($quest)){
      $this->queet = $quest;
    }
    if(isset($wait_count)){
      $this->wait_count = $wait_count;
    }
  }

  public function setFromJson($json_data){
    if(isset($json_data['quiz_uid'])){
      $this->quiz_uid = $json_data['quiz_uid'];
    }
    if(isset($json_data['quest'])){
      $this->quest = $json_data['quest'];
    }
    if(isset($json_data['user_uid'])){
      $this->user_uid = $json_data['user_uid'];
    }
    if(isset($json_data['wait_count'])){
      $this->wait_count = $json_data['wait_count'];
    }
    if(isset($json_data['correct_uid'])){
      $this->correct_uid = $json_data['correct_uid'];
    }
    if(isset($json_data['show_num'])){
      $this->show_num = $json_data['show_num'];
    }
    if(isset($json_data['step_uid'])){
      $this->step_uid = $json_data['step_uid'];
    }
    if(isset($json_data['user_count'])){
      $this->user_count = $json_data['user_count'];
    }
    if(isset($json_data['view_content'])){
      $this->correct_view_content = $json_data['view_content'];
    }
    if(isset($json_data['seq_no'])){
      $this->correct_view_seq = $json_data['seq_no'];
    }

  }

  public function setFromColumn($quiz_data){
    if(isset($quiz_data['f_quiz_uid'])){
      $this->quiz_uid = $quiz_data['f_quiz_uid'];
    }
    if(isset($quiz_data['f_quest'])){
      $this->quest = $quiz_data['f_quest'];
    }
    if(isset($quiz_data['f_user_uid'])){
      $this->user_uid = $quiz_data['f_user_uid'];
    }
    if(isset($quiz_data['f_wait_count'])){
      $this->wait_count = $quiz_data['f_wait_count'];
    }
    if(isset($quiz_data['f_correct_uid'])){
      $this->correct_uid = $quiz_data['f_correct_uid'];
    }
    if(isset($quiz_data['f_show_num'])){
      $this->show_num = $quiz_data['f_show_num'];
    }
    if(isset($quiz_data['f_reg_date'])){
      $this->reg_date= $quiz_data['f_reg_date'];
    }
    if(isset($quiz_data['f_step_uid'])){
      $this->step_uid = $quiz_data['f_step_uid'];
    }
    if(isset($quiz_data['user_count'])){
      $this->user_count = $quiz_data['user_count'];
    }
    if(isset($quiz_data['f_view_content'])){
      $this->correct_view_content = $quiz_data['f_view_content'];
    }
    if(isset($quiz_data['f_seq_no'])){
      $this->correct_view_seq = $quiz_data['f_seq_no'];
    }

  }

  public function getJson( $view_list = null ){
    $json = '{';
    $json .= '"quiz_uid":'.$this->quiz_uid;
    $json .= ',"quest":"'.addslashes($this->quest).'"';
    $json .= ',"user_uid":'.$this->user_uid;
    $json .= ',"wait_count":'.$this->wait_count;
    $json .= ',"correct_uid":'.$this->correct_uid;
    $json .= ',"show_num":'.$this->show_num;
    $json .= ',"step_uid":'.$this->step_uid;
    if( isset($view_list) ){
      $json .= ',"view_list":'.$view_list;
    }
    $json .= ',"f_reg_date":"'.$this->reg_date.'"';
    $json .= ',"user_count":'.$this->user_count;
    $json .= ',"correct_view_content":"'.$this->correct_view_content.'"';
    $json .= ',"correct_view_seq":'.$this->correct_view_seq;
    $json .= '}';
    return $json;
  }
}
?>
