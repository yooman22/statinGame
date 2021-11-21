<?php
class Mparksearch {

  public $keyword = "";
  public $type = "";
  public $mode = "";

  public function setFromJson( $get_data ){
    if( isset($get_data) ){
      if(isset($get_data['keyword'])){
        $this->keyword = $get_data['keyword'];
      }
      if(isset($get_data['type'])){
        $this->type = $get_data['type'];
      }
      if(isset($get_data['mode'])){
        $this->mode = $get_data['mode'];
      }
    }
  }

}
?>
