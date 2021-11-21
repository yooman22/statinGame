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
$user = $mpark->getUserInfo($_GET['user_uid']);
$step = $mpark->selectStep( $_GET['step_uid'] );
$answer_list = $mpark->listUserQuiz( $step->step_uid, $user->user_uid );
?>

<script>
$(document).ready(function(){
  //openUserAdd();
});
</script>
<h5><span class="list-title-point"><a href="./step_result.php?step_uid=<?php echo $step->step_uid;?>">"<?php echo $step->step_title; ?>"</a></span></h5>
<h6><span class="list-title-point"><?php echo $user->user_knick; ?>(<?php echo $user->user_id; ?>)</span> 결과</h6>
<table class="table table-bordered">
  <colgroup>
    <col width="50" />
    <col width="auto" />
    <col width="80" />
  </colgroup>
  <thead>
    <tr>
      <th>순번</th>
      <th>문제</th>
      <th>정답여부</th>
    </tr>
  </thead>
  <tbody>
<?php
$n = sizeof($answer_list);
$correct_count = 0;
$incorrect_count = 0;
foreach( $answer_list as $answer ){
?>
    <tr>
      <td><?php echo $n; ?></td>
      <td><?php echo $answer->quest; ?></td>
      <td>
          <?php
          if( $answer->correct_uid == $answer->view_uid ){
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
  <tr>
    <td colspan="5" class="list-title-point">정답 : <?php echo $correct_count; ?> / 오답 : <?php echo $incorrect_count;?></td>
  </tr>
  </tbody>
</table>




<?php
 include_once("../include/footer.php");
?>
