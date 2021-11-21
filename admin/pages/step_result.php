<?php
require_once("../conf/config.php");
require_once("../../conf/dbconn.php");
require_once("../../class/class.mpark.php");
require_once("../../class/class.mparkquiz.php");
require_once("../../class/class.mparkquizview.php");
require_once("../../class/class.mparkuser.php");
require_once("../../class/class.mparkstep.php");
require_once("../../class/class.mparksearch.php");
include_once("../include/header.php");
$mpark = new Mpark( $dbconn );
$step = $mpark->selectStep( $_GET['step_uid'] );
$quiz_list = $mpark->selectStepedQuizResultList( $_GET['step_uid'] );
?>

<script>
$(document).ready(function(){
  //openUserAdd();
});
</script>
<h5><span class="list-title-point">"<?php echo $step->step_title; ?>"</span> 퀴즈목록</h5>
<table class="table table-bordered">
  <colgroup>
    <col width="50" />
    <col width="auto" />
    <col width="80" />
  </colgroup>
  <thead>
    <tr>
      <th>순번</th>
      <th>문항</th>
      <th>정답자 수</th>
    </tr>
  </thead>
  <tbody>
<?php
foreach( $quiz_list as $item ){
?>
    <tr>
      <td><?php echo $item->show_num; ?></td>
      <td><a href="./quiz_result.php?step_uid=<?php echo $item->step_uid; ?>&quiz_uid=<?php echo $item->quiz_uid; ?>"><?php echo $item->quest; ?></a></td>
      <td class="number"><?php echo $item->user_count; ?></td>
    </tr>
<?php
}
?>
  </tbody>
</table>




<?php
 include_once("../include/footer.php");
?>
