<?php
require_once("../conf/config.php");
require_once("../../conf/dbconn.php");
require_once("../../class/class.mpark.php");
require_once("../../class/class.mparkuserlog.php");
require_once("../../class/class.mparksearch.php");
include_once("../include/header.php");
?>

<script>
$(document).ready(function(){
  //openUserAdd();
  $('#userSearchDate').datepicker(
    { dateFormat: 'yy-mm-dd' }
  );
});
</script>

<?php

$mpark = new Mpark( $dbconn );
$search_key = '';
if( isset($_GET['q']) ){
  $search_key = $_GET['q'];
}
$search_date = '';
if( isset($_GET['d']) ){
  $search_date = $_GET['d'];
}
$log_count = 0;
$user_log_list = null;
if($search_key || $search_date){
  $user_log_list = $mpark->userLogList($search_date,$search_key);
  $log_count = sizeof($user_log_list);
}
?>

<div class="list-search">
  <form class="form-inline" action="./user_log_list.php" method="get">
    <div class="form-row" style="width:100%;">
      <!--<div>
        <select class="form-control" name="opt">
          <option value="name">제목</option>
          <option value="id">날짜</option>
        </select>
      </div>-->
      <div class="row" style="width:100%;">
        <div class="col-md-12">
          <input type="text" name="d" class="form-control mb-2 mr-sm-2" id="userSearchDate" value="<?php echo $search_date; ?>" placeholder="날짜" style="width:100px;" autocomplete="off">
          <input type="text" name="q" class="form-control mb-2 mr-sm-2" id="userSearchText" value="<?php echo $search_key; ?>" placeholder="검색할 아이디/별명을 입력해 주세요." style="width:300px;">
          <button type="submit" class="btn btn-dark mb-2">조회</button>
        </div>
      </div>
    </div>
  </form>
</div>

<h5>
    접속자로그
</h5>
<table class="table table-bordered">
  <colgroup>
    <col width="30" />
    <col width="50" />
    <col width="auto" />
  </colgroup>
  <thead>
    <tr>
      <th>#</th>
      <th>UID</th>
      <th>SESSION ID</th>
      <th>아이디</th>
      <th>이름</th>
      <th>별명</th>
      <td>최초접속</th>
      <td>최종종료</th>
      <td>최종-최초</th>
    </tr>
  </thead>
  <tbody>
<?php
if($search_key || $search_date):
$n = $log_count;
foreach( $user_log_list as $v):
?>
    <tr>
      <td><?php echo $n; ?></td>
      <td><?php echo $v->user_uid;?></td>
      <td><?php echo $v->session_id;?></td>
      <td><a href="./user_log_list_detail.php?d=&q=<?php echo $v->user_id;?>"><?php echo $v->user_id;?></a></td>
      <td><?php echo $v->user_name;?></td>
      <td><?php echo $v->user_knick;?></td>
      <td><?php echo $v->login_date; ?></td>
      <td><?php echo $v->logout_date; ?></td>
      <td><?php echo $v->remain_date."일 ".$v->remain_time; ?></td>
    </tr>
<?php
$n--;
endforeach;
endif;
?>
  </tbody>
</table>

<?php
 include_once("../include/footer.php");
?>
