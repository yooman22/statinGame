<?php
require_once("../conf/config.php");
require_once("../../conf/dbconn.php");
require_once("../../class/class.mpark.php");
require_once("../../class/class.mparkquiz.php");
require_once("../../class/class.mparkquizview.php");
require_once("../../class/class.mparkuser.php");
require_once("../../class/class.mparkstep.php");
require_once("../../class/class.mparksearch.php");
require_once("../../class/class.mparkanswer.php");
include_once("../include/header.php");
$mpark = new Mpark( $dbconn );
$step = $mpark->selectStep( $_GET['step_uid'] );
$quiz = $mpark->selectQuiz( $_GET['quiz_uid'] );

$correct_status = 0;
if( isset($_GET['correct_status']) ){
  $correct_status = (int)$_GET['correct_status'];
}
$user_list = $mpark->listQuizUser( $step->step_uid, $quiz->quiz_uid, $correct_status );
?>

<script>
$(document).ready(function(){
  //openUserAdd();
});
</script>
<h5><span class="list-title-point"><a href="./step_result.php?step_uid=<?php echo $step->step_uid;?>">"<?php echo $step->step_title; ?>"</a></span></h5>
<h5><span class="list-title-point"><a href="./quiz_result.php?step_uid=<?php echo $step->step_uid; ?>&quiz_uid=<?php echo $quiz->quiz_uid;?>"><?php echo $quiz->quest; ?></a></span>
  &nbsp;
  <span style="color:red;">
  <?php
  switch($correct_status){
    case 0:
      echo '전체결과';
      break;
    case 1:
      echo '정답결과';
      break;
    case 2:
      echo '오답결과';
      break;
  }
  ?>
</span>
</h5>
<table class="table table-bordered">
  <colgroup>
    <col width="50" />
    <col width="auto" />
    <col width="80" />
  </colgroup>
  <thead>
    <tr>
      <th>순번</th>
      <th>담당MR명</th>
      <th>아이디</th>
      <th>거래처명</th>
      <th>정답여부</th>
    </tr>
  </thead>
  <tbody>
<?php
$n = sizeof($user_list);
$correct_count = 0;
$incorrect_count = 0;
foreach( $user_list as $user ){
?>
    <tr>
      <td><?php echo $n; ?></td>
      <td><?php echo $user->user_knick; ?></td>
      <td><?php echo $user->user_id; ?></td>
      <td class="number"><?php echo $user->user_hospital; ?></td>
      <td>
          <?php
          if( $user->correct_uid == $user->view_uid ){
            $correct_count++;
            echo 'O';
          }else{
            $incorrect_count++;
            echo 'X';
          }
          ?>
      </td>
    </tr>
<?php
$n--;
}
?>
  </tbody>
</table>
<div style="height:50px;">
&nbsp;
</div>
<div class="" style="display:block;position:fixed;left:10px;right:10px;height:35px;bottom:5px;background-color:#FFF;border:1px solid #555;padding:5px 20px;line-height:30px;border-radius:5px;">
  <a href="./quiz_result.php?step_uid=<?php echo $step->step_uid; ?>&quiz_uid=<?php echo $quiz->quiz_uid;?>">전체목록</a>
  &nbsp;&nbsp;
  <?php if($correct_status != 2): ?>
  정답 : <a href="./quiz_result.php?step_uid=<?php echo $step->step_uid; ?>&quiz_uid=<?php echo $quiz->quiz_uid;?>&correct_status=1"><?php echo $correct_count; ?></a>
   &nbsp;&nbsp;
   <?php endif;?>
   <?php if($correct_status != 1): ?>
   오답 : <a href="./quiz_result.php?step_uid=<?php echo $step->step_uid; ?>&quiz_uid=<?php echo $quiz->quiz_uid;?>&correct_status=2"><?php echo $incorrect_count;?></a>
   <?php endif;?>
</div>



<?php
 include_once("../include/footer.php");
?>
