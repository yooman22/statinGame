<?php
require_once("../conf/config.php");
include_once("../include/header.php");
include_once("../include/function.php");

require_once("../../conf/dbconn.php");
require_once("../../class/class.mpark.php");
require_once("../../class/class.mparkquiz.php");
require_once("../../class/class.mparkquizview.php");
require_once("../../class/class.mparkuser.php");
require_once("../../class/class.mparkstep.php");
require_once("../../class/class.mparksearch.php");


$mpark = new Mpark( $dbconn );
$user_search = new Mparkuser();
$opt = "";
$q = "";
if( isset($_GET['opt']) ){
  $opt = $_GET['opt'];
  if( $_GET['opt'] != ''){
    if( $_GET['opt'] == 'user_name' ){
      $user_search->user_name = $q = $_GET['q'];
    }
    if( $_GET['opt'] == 'user_id' ){
      $user_search->user_id = $q = $_GET['q'];
    }
    if( $_GET['opt'] == 'user_phone' ){
      $user_search->user_phone = $q = $_GET['q'];
    }
    if( $_GET['opt'] == 'user_knick' ){
      $user_search->user_knick = $q = $_GET['q'];
    }
  }
}
$user_total = $mpark->getUserTotal( $user_search );
$start = 0;
$ppr = 30;
if( isset($_GET['s']) ){
  $start = $_GET['s'];
}
$num = $user_total - $start;
$limit = $ppr;
$list = $mpark->selectUser( $user_search, $start, $limit );
?>

<script>
$(document).ready(function(){
  //openUserAdd();
});
</script>

<div class="btn-navi">
  <button type="button" class="btn btn-secondary" onclick="jumpUrl('../../admin/pages/user_list.php?opt=user_name&q=&s=0')">사용자 목록</button>
  <button type="button" class="btn btn-primary" onclick="openUserAdd()">사용자 추가</button>
</div>
<div class="list-search">
  <form class="form-inline" action="?a=1" method="get" action="./">
    <div class="form-row" style="width:100%;">
      <div>
        <select class="form-control" name="opt">
          <option value="user_id" <?php printSelected($opt,'user_id'); ?>>아이디</option>
          <option value="user_name" <?php printSelected($opt,'user_cscode'); ?>>거래처코드</option>
          <option value="user_name" <?php printSelected($opt,'user_hospital'); ?>>거래처명</option>
          <option value="user_name" <?php printSelected($opt,'user_mrname'); ?>>담당MR명</option>
		  <!--
          <option value="user_name" <?php printSelected($opt,'user_name'); ?>>이름</option>
          <option value="user_phone" <?php printSelected($opt,'user_phone'); ?>>전화번호</option>
          <option value="user_knick" <?php printSelected($opt,'user_knick'); ?>>별명</option>
		  -->
        </select>
      </div>
      <div>
        <input type="text" name="q" class="form-control mb-2 mr-sm-2" id="userSearchText" placeholder="검색어를 입력해 주세요." value="<?php echo $q; ?>">
        <button type="submit" class="btn btn-dark mb-2">조회</button>
      </div>
      <div>
        <a href="../../admin/pages/user_excel_down.php" class="btn btn-dark mb-2" style="margin-left:5px;">엑셀다운</a>
      </div>
    </div>
  </form>
</div>

<h5>사용자 목록</h5>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>#</th>
      <th>아이디</th>
      <th>거래처코드</th>
      <th>거래처명</th>
      <th>담당MR명</th>
	  <!--
      <th>이름</th>
      <th>별명</th>
      <th>전화번호</th>
      <th>지역</th>
      <th>병원명</th>
      <th>진료과</th>
      <th>Email</th>
	  -->
      <th>LEVEL</th>
      <th>등록일시</th>
      <th>관리</th>
    </tr>
  </thead>
  <tbody>
<?php
foreach( $list as $user ) :
?>
    <tr>
      <td><?php echo $num; ?></td>
      <td><a href="javascript:;" onclick="openUserEdit(<?php echo $user->user_uid; ?>)"><?php echo $user->user_id; ?></a></td>
      <th><?php echo $user->user_cscode; ?></th>
      <th><?php echo $user->user_hospital; ?></th>
      <th><?php echo $user->user_mrname; ?></th>
	  <!--
      <td><a href="javascript:;" onclick="openUserEdit(<?php echo $user->user_uid; ?>)"><?php echo $user->user_name; ?></a></td>
      <th><?php echo $user->user_knick; ?></th>
      <th><?php echo $user->user_phone; ?></th>
      <th><?php echo $user->user_area; ?></th>
      <th><?php echo $user->user_depart; ?></th>
      <td><?php echo $user->user_email; ?></td>
	  -->
      <td><?php echo getUserLevelName($user->user_level); ?></td>
      <td><?php echo $user->reg_date; ?></td>
      <td>
        <button type="button" class="btn btn-danger" onclick="deleteUser(<?php echo $user->user_uid; ?>,'<?php echo $user->user_id; ?>')">삭제</button>
        <a href="./user_log_list.php?d=<?php echo getDateFromTime('');?>&q=<?php echo $user->user_id;?>" class="btn btn-primary">접속기록</a>
      </td>
    </tr>
<?php
$num--;
endforeach;
?>
  <tr>
    <td colspan="8">
<?php
if( $start < $user_total && $start > 0 ){
  echo '<a href="/admin/pages/user_list.php?opt='.$opt.'&q='.$q.'&s='.($start - $ppr).'">[ 이전 페이지 ]</a>';
}
if($num >= 1){
  echo '<a href="/admin/pages/user_list.php?opt='.$opt.'&q='.$q.'&s='.($start + $ppr).'">[ 다음 페이지 ]</a>';
}
?>
    </td>
  </tr>
  </tbody>
</table>




<?php
 include_once("../include/footer.php");
?>
