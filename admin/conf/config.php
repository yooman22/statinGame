<?php
include_once("../include/function.php");

include_once("../../conf/config.php");
require_once("../../class/class.admin.menu.php");

$nav_arr = array(
  new AdminMenu("HOME","../../admin/pages/dashboard.php","home")
  ,new AdminMenu("퀴즈차시관리","../../admin/pages/step_list.php","step")
  ,new AdminMenu("퀴즈관리","../../admin/pages/quiz_list.php","quiz")
  ,new AdminMenu("사용자관리","../../admin/pages/user_list.php","user")
//  ,new AdminMenu("사용자접속로그","/admin/pages/user_log_list.php","user_log")
  ,new AdminMenu("퀴즈데이터처리","../../admin/pages/monitor.php","monitor")
  ,new AdminMenu("방송페이지열기","../../front/pages/live.php","live","_blank")
);
$current_url = $_SERVER['REQUEST_URI'];
 ?>
