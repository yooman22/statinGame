<?php
require_once("../conf/config.php");
include_once("../include/header.php");


require_once("../../conf/dbconn.php");
require_once("../../class/class.mpark.php");
require_once("../../class/class.mparkquiz.php");
require_once("../../class/class.mparkquizview.php");
require_once("../../class/class.mparkuser.php");
require_once("../../class/class.mparkstep.php");
require_once("../../class/class.mparksearch.php");

$mpark = new Mpark( $dbconn );
$step_list = $mpark->listStep( $_GET );
?>

<script>
$(document).ready(function(){
  //openUserAdd();
});
</script>
<div class="btn-navi">
  <button type="button" class="btn btn-secondary" onclick="jumpUrl('../../admin/pages/step_list.php')">전체목록</button>
  <button type="button" class="btn btn-primary" onclick="openStepAdd()">차시추가</button>
  <button type="button" class="btn btn-danger" onclick="deleteSelectedStep()">선택삭제</button>
</div>
<div class="list-search">
  <form class="form-inline" action="?a=1" method="get">
    <div class="form-row" style="width:100%;">
      <div>
        <select class="form-control" name="opt">
          <option value="name">제목</option>
          <option value="id">영상 URL</option>
        </select>
      </div>
      <div>
        <input type="text" name="q" class="form-control mb-2 mr-sm-2" id="userSearchText" placeholder="제목을 입력해 주세요.">
        <button type="submit" class="btn btn-dark mb-2">조회</button>
      </div>
    </div>
  </form>
</div>

<h5>퀴즈차시 목록</h5>
<table class="table table-bordered">
  <colgroup>
    <col width="50" />
    <col width="auto" />
    <col width="auto" />
    <col width="160" />
  </colgroup>
  <thead>
    <tr>
      <th><input type="checkbox" name="check_all" onclick="checkAllStep(event)" /></th>
      <th>차시명</th>
      <th>영상 URL</th>
      <th>방송일시</th>
    </tr>
  </thead>
  <tbody>
<?php
foreach( $step_list as $item ){
?>
    <tr>
      <td><input type="checkbox" name="step_check" value="<?php echo $item->step_uid; ?>" /></td>
      <td><a href="javascript:;" onclick="openStepEdit(<?php echo $item->step_uid; ?>)"><?php echo $item->step_title; ?></a></td>
      <td><a href="<?php echo $item->step_video; ?>" target="_blank"><?php echo $item->step_video; ?></a></td>
      <td><?php echo $item->step_date; ?></td>
    </tr>
    <tr>
      <td colspan="4" style="text-align:right;background-color:#EFEFEF;border-bottom:1px solid #555;">
        <button type="button" class="btn btn-primary" onclick="jumpUrl('./step_result.php?step_uid=<?php echo $item->step_uid; ?>')">결과조회</button>
        <button type="button" class="btn btn-success" onclick="jumpUrl('./step_participant.php?step_uid=<?php echo $item->step_uid; ?>')">참가(당첨)자조회</button>
        <button type="button" class="btn btn-warning" onclick="jumpUrl('<?php echo getNavUrl('quiz'); ?>?type=select_quiz&step=<?php echo $item->step_uid; ?>')">문항선택</button>
        <button type="button" class="btn btn-danger" onclick="openStepEdit(<?php echo $item->step_uid; ?>)">수정</button>
        <button type="button" class="btn btn-primary" onclick="copyStep(<?php echo $item->step_uid; ?>)">복사</button>
        <button type="button" class="btn btn-danger" onclick="truncateStep(<?php echo $item->step_uid; ?>)">데이터초기화</button>
      </td>
    </tr>
<?php
}
?>
  </tbody>
</table>
  



<?php
 include_once("../include/footer.php");
?>
