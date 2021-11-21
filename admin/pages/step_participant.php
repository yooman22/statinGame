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
$user_list = $mpark->listStepParticipant( $step->step_uid );
//var_dump($step);
?>

<script>
$(document).ready(function(){
  //openUserAdd();
});
</script>
<h5><span class="list-title-point"><a href="./step_result.php?step_uid=<?php echo $step->step_uid;?>">"<?php echo $step->step_title; ?>"</a></span></h5>
<table class="table table-bordered">
  <colgroup>
    <col width="50" />
    <col width="50" />
    <col width="auto" />
  </colgroup>
  <thead>
    <tr>
      <th>순번</th>
      <th>순위</th>
      <th>담당MR명</th>
      <th>아이디</th>
      <th>거래처명</th>
      <th>거래처코드</th>
      <th>정답수</th>
    </tr>
  </thead>
  <tbody>
<?php
$n = sizeof($user_list);
$num = 0;
foreach( $user_list as $user ){
$num++;
$row_class = '';
if($num <= $step->step_wincount ){
  $row_class = 'winner';
}
?>
    <tr class="<?php echo $row_class; ?>">
      <td><?php echo $n; ?></td>
      <td><?php echo $num; ?></td>
      <td><a href="./user_log_list.php?d=&q=<?php echo $user->user_id;?>"><?php echo $user->user_mrname; ?></a></td>
      <td><?php echo $user->user_id; ?></td>
      <td class="number"><?php echo $user->user_hospital; ?></td>
      <td><?php echo $user->user_cscode; ?></td>
      <td><a href="./user_result.php?step_uid=<?php echo $step->step_uid;?>&user_uid=<?php echo $user->user_uid;?>"><?php echo $user->correct_count; ?></a></td>
    </tr>
<?php
$n--;
}
?>
  </tbody>
</table>




<?php
 include_once("../include/footer.php");
?>
