<?php
require_once("../conf/config.php");
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
$ppr = 999999999;
if( isset($_GET['s']) ){
  $start = $_GET['s'];
}
$num = $user_total - $start;
$limit = $ppr;
$list = $mpark->selectUser( $user_search, $start, $limit );

$ti = date('Ymd_His');
header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename={$ti}_member_list.xls" );
?>
<meta http-equiv="Content-Type" content="application/vnd.ms-excel;charset=UTF-8">
	<table border="0" cellpadding="0" cellspacing="0" width="3105" style="font-family: &quot;Malgun Gothic&quot;; border-collapse: collapse; table-layout: fixed; width: 2330pt;">
		<tr style="height: 30.5pt;">
			<td class="xl67" width="83" style="padding-top: 1px; padding-right: 1px; padding-left: 1px; font-size: 9pt; font-family: &quot;맑은 고딕&quot;, monospace; vertical-align: middle; border: 0.5pt solid windowtext; text-align: center; height: 30.5pt; width: 62pt;">번호</td>
			<td class="xl68" width="84" style="padding-top: 1px; padding-right: 1px; padding-left: 1px; font-size: 9pt; font-family: &quot;맑은 고딕&quot;, monospace; vertical-align: middle; border-top: 0.5pt solid windowtext; border-right: 0.5pt solid windowtext; border-bottom: 0.5pt solid windowtext; border-left: none; border-image: initial; white-space: nowrap; text-align: center; width: 63pt;">아이디</td>
			<td class="xl67" width="121" style="padding-top: 1px; padding-right: 1px; padding-left: 1px; font-size: 9pt; font-family: &quot;맑은 고딕&quot;, monospace; vertical-align: middle; border-top: 0.5pt solid windowtext; border-right: 0.5pt solid windowtext; border-bottom: 0.5pt solid windowtext; border-left: none; border-image: initial; text-align: center; width: 91pt;">거래처코드</td>
			<td class="xl67" width="201" style="padding-top: 1px; padding-right: 1px; padding-left: 1px; font-size: 9pt; font-family: &quot;맑은 고딕&quot;, monospace; vertical-align: middle; border-top: 0.5pt solid windowtext; border-right: 0.5pt solid windowtext; border-bottom: 0.5pt solid windowtext; border-left: none; border-image: initial; text-align: center; width: 180pt;">거래처명</td>
			<td class="xl67" width="121" style="padding-top: 1px; padding-right: 1px; padding-left: 1px; font-size: 9pt; font-family: &quot;맑은 고딕&quot;, monospace; vertical-align: middle; border-top: 0.5pt solid windowtext; border-right: 0.5pt solid windowtext; border-bottom: 0.5pt solid windowtext; border-left: none; border-image: initial; text-align: center; width: 91pt;">담당MR명</td>
			<td class="xl70" width="88" style="padding-top: 1px; padding-right: 1px; padding-left: 1px; font-size: 9pt; font-family: &quot;맑은 고딕&quot;, monospace; vertical-align: middle; border-top: 0.5pt solid windowtext; border-right: 0.5pt solid windowtext; border-bottom: 0.5pt solid windowtext; border-left: none; border-image: initial; text-align: center; width: 66pt;">LEVEL</td>
		</tr>
	<?php
	foreach( $list as $user ) :
	?>
		<tr height="22" style="height: 16.5pt;">
			<td height="22" class="xl82" width="83" style="padding-top: 1px; padding-right: 1px; padding-left: 1px; font-size: 11pt; font-family: &quot;맑은 고딕&quot;, monospace; vertical-align: middle; border-top: none; border-right: 0.5pt solid windowtext; border-bottom: 0.5pt solid windowtext; border-left: none; border-image: initial; white-space: nowrap; text-align: center;"><?php echo $num; ?></td>
			<td class="xl81" style="padding-top: 1px; padding-right: 1px; padding-left: 1px; font-size: 11pt; font-family: &quot;맑은 고딕&quot;, monospace; vertical-align: middle; border-top: none; border-right: 0.5pt solid windowtext; border-bottom: 0.5pt solid windowtext; border-left: none; border-image: initial; white-space: nowrap; text-align: center;"><?php echo $user->user_id; ?></td>
			<td class="xl81" style="padding-top: 1px; padding-right: 1px; padding-left: 1px; font-size: 11pt; font-family: &quot;맑은 고딕&quot;, monospace; vertical-align: middle; border-top: none; border-right: 0.5pt solid windowtext; border-bottom: 0.5pt solid windowtext; border-left: none; border-image: initial; white-space: nowrap; text-align: center;"><?php echo $user->user_cscode; ?></td>
			<td class="xl81" style="padding-top: 1px; padding-right: 1px; padding-left: 1px; font-size: 11pt; font-family: &quot;맑은 고딕&quot;, monospace; vertical-align: middle; border-top: none; border-right: 0.5pt solid windowtext; border-bottom: 0.5pt solid windowtext; border-left: none; border-image: initial; white-space: nowrap; text-align: center;"><?php echo $user->user_hospital; ?></td>
			<td class="xl81" style="padding-top: 1px; padding-right: 1px; padding-left: 1px; font-size: 11pt; font-family: &quot;맑은 고딕&quot;, monospace; vertical-align: middle; border-top: none; border-right: 0.5pt solid windowtext; border-bottom: 0.5pt solid windowtext; border-left: none; border-image: initial; white-space: nowrap; text-align: center;"><?php echo $user->user_mrname; ?></td>
			<td class="xl81" style="padding-top: 1px; padding-right: 1px; padding-left: 1px; font-size: 11pt; font-family: &quot;맑은 고딕&quot;, monospace; vertical-align: middle; border-top: none; border-right: 0.5pt solid windowtext; border-bottom: 0.5pt solid windowtext; border-left: none; border-image: initial; white-space: nowrap; text-align: center;"><?php echo getUserLevelName($user->user_level); ?></td>
		</tr>
	<?php $num--; endforeach; ?>
</table>