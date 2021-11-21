<?php
class AdminMenu {

  public $title = "";
  public $url = "";
  public $slug = "";
  public $target = "";

  function __construct( $title, $url, $slug, $target = "" ){
    $this->title = $title;
    $this->url = $url;
    $this->slug = $slug;
    $this->target = $target;
  }
}
?>
