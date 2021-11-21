<?php
class MparkquizView extends Mparkquiz {
  public $view_uid = 0;
  public $view_content = '';
  public $seq_no = 0;
  public $modify_date = '';
  public $user_uid = 0;

  function __construct( $quiz_uid = null, $view_content = null, $seq_no = null ){
    if(isset($quiz_uid)){
      if( $quiz_uid > 0 ){
        $this->quiz_uid = $quiz_uid;
      }
    }

    if(isset($view_content)){
      $this->view_content = $view_content;
    }
    if(isset($seq_no)){
      $this->seq_no = $seq_no;
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
    if(isset($json_data['view_uid'])){
      $this->view_uid = $json_data['view_uid'];
    }
    if(isset($json_data['view_content'])){
      $this->view_content = $json_data['view_content'];
    }
    if(isset($json_data['seq_no'])){
      $this->seq_no = $json_data['seq_no'];
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
    if(isset($quiz_data['f_winner_count'])){
      $this->winner_count = $quiz_data['f_winner_count'];
    }
    if(isset($quiz_data['f_reg_date'])){
      $this->reg_date= $quiz_data['f_reg_date'];
    }
    if(isset($quiz_data['f_view_uid'])){
      $this->view_uid = $quiz_data['f_view_uid'];
    }
    if(isset($quiz_data['f_view_content'])){
      $this->view_content = $quiz_data['f_view_content'];
    }
    if(isset($quiz_data['f_seq_no'])){
      $this->seq_no = $quiz_data['f_seq_no'];
    }

  }

  public function getJson( $view_list = null ){
    $json = '{';
    $json .= '"quiz_uid":'.$this->quiz_uid;
    $json .= ',"quest":"'.addslashes($this->quest).'"';
    $json .= ',"user_uid":'.$this->user_uid;
    $json .= ',"wait_count":'.$this->wait_count;
    $json .= ',"correct_uid":'.$this->correct_uid;
    $json .= ',"view_uid":'.$this->view_uid;
    $json .= ',"view_content":"'.addslashes($this->view_content).'"';
    $json .= ',"seq_no":"'.$this->seq_no.'"';
    $json .= '}';
    return $json;
  }
}
?>
