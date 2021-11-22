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
?>

<script>
$(document).ready(function(){
  //openUserAdd();
});
</script>

<?php

$mpark = new Mpark( $dbconn );
$step = new Mparkstep();
$step->step_uid = 0;
if( isset( $_GET['type'] ) ){
  if( $_GET['type'] == 'select_quiz'){
    if( isset( $_GET['step'] ) ){
      $step = $mpark->selectStep( $_GET['step'] );
    }
  }
}
?>

<div class="btn-navi">
  <button type="button" class="btn btn-secondary" onclick="jumpUrl('../../admin/pages/quiz_list.php')">전체목록</button>
  <button type="button" class="btn btn-primary" onclick="openQuizAdd()">퀴즈추가</button>
  <button type="button" class="btn btn-danger" onclick="deleteSelectedQuiz()">선택삭제</button>
</div>
<div class="list-search">
  <form class="form-inline" action="?a=1" method="get">
    <div class="form-row" style="width:100%;">
      <div>
        <select class="form-control" name="opt">
          <option value="name">제목</option>
          <option value="id">날짜</option>
        </select>
      </div>
      <div>
        <input type="text" name="q" class="form-control mb-2 mr-sm-2" id="userSearchText" placeholder="제목을 입력해 주세요.">
        <button type="submit" class="btn btn-dark mb-2">조회</button>
      </div>
    </div>
  </form>
</div>

<h5>
    퀴즈 목록
<?php
if( isset($step) ){
  if( $step->step_uid ){
    echo '( "'.$step->step_title.'"에 선택중... )';
    echo '&nbsp;&nbsp;<button type="button" class="btn btn-primary" onclick="saveStepQuiz('.$step->step_uid.')">차시저장</button>';
  }
}
?>
</h5>
<table class="table table-bordered">
  <colgroup>
    <col width="30" />
    <col width="auto" />
    <col width="80" />
    <col width="160" />
  </colgroup>
  <thead>
    <tr>
      <th><input type="checkbox" name="check_all" onclick="checkAllQuiz(event)" /></th>
      <th>문제</th>
      <th>대기시간</th>
      <th>등록일시</th>
    </tr>
  </thead>
  <tbody>
<?php
$list = $mpark->selectQuizList($step->step_uid);
foreach( $list as $v):
?>
    <tr>
      <td><input type="checkbox" name="quiz_check" value="<?php echo $v->quiz_uid;?>" <?php if($step->step_uid > 0 && $v->step_uid == $step->step_uid){echo ' checked';} ?> /></td>
      <td class="quiz_quest"><a href="javascript:;" onclick="showQuiz(<?php echo $v->quiz_uid;?>)"><?php echo $v->quest; ?></a></td>
      <td><?php echo $v->wait_count; ?>초</td>
      <td><?php echo $v->reg_date; ?></td>
    </tr>
<?php
endforeach;
?>
  </tbody>
</table>

<?php
 include_once("../include/footer.php");
?>
