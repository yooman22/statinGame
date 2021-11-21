<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">

  <title>듀비에 - 라이브 퀴즈쇼</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
  <link rel="stylesheet" href="./lib/jquery-ui-1.12.1/jquery-ui.min.css" />
  <link rel="stylesheet" href="./css/style.css?v=<?php echo time(); ?>" />
  <script src="./lib/jquery-3.4.1.min.js"></script>
  <script src="./lib/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script src="./js/script.js?v=<?php echo time(); ?>"></script>
  <script>
  if ($(window).width() < 1024 || $(window).height() < 500) {
    $('head').append('<meta name="viewport" content="width=device-width, initial-scale=0.5, minimum-scale=0, maximum-scale=2, user-scalable=yes, target-dencitydpi=device-dpi">');
  }else{
    $('head').append('<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=0, maximum-scale=2, user-scalable=yes, target-dencitydpi=device-dpi">');
  }
  </script>



<?php
session_start();
include_once("./conf/dbconn.php");
include_once("./conf/config.php");

include_once("./include/function.php");

include_once("./class/class.mpark.php");
include_once("./class/class.mparkuser.php");
include_once("./class/class.mparkuser.php");
include_once("./class/class.mparkstatus.php");
include_once("./class/class.mparkstep.php");
include_once("./class/class.mparksearch.php");


$user = new Mparkuser( null );
$user->setFromSession();

if( !$user->user_uid ){
  header('Location:./');
}

$mpark = new Mpark($dbconn);

$ready_status = $mpark->getReadyStatus('live_ready');
$ready_notice = $mpark->getReadyStatus('live_notice');


//퀴즈쇼 차시(방송 title, 유튜브url)
$step_onair = $mpark->getOnairStep();

if( $step_onair ){
  $step = new Mparkstep($step_onair);
}else{
  $step = 0;
}
?>
<script src="./js/livest.websocket.js?v=<?php echo time(); ?>"></script>
<script src="./js/live.video.js?v=<?php echo time(); ?>"></script>
<script src="./js/live.admin.js?v=<?php echo time(); ?>"></script>
<script src="./js/live.user.js?v=<?php echo time(); ?>"></script>

<style>
header{
  background-color:unset;
  position:absolute;
  top:0px;
  width:75%;
  left:0px;
  z-index:1000;
  max-width:1620px;
}

div.quest-container {
  position:absolute;
  left:0px;
  top:0px;
  width:100%;
  height:100%;
  background-image: url('/images/bg_quest.png');
  display:none;
  z-index:1001;
}

div.btn_start {
  position: absolute;
  left: 50%;
  top: 50%;
  margin-top: -50px;
  margin-left: -50px;
}

button.btn_start {
  width:100px;
  height:100px;
}

div#id_btn_start{
  background-image:url('/images/bg_start_layer.png');
  width:100%;
  height:100%;
}

</style>
<script>
var livestsocket = new LivestWebsocket('175.123.253.156',18080);
livestsocket.connect( { onmessage:onMessage,onopen:onOpenConnect } );
var livestchat = new LivestWebsocket('175.123.253.156',18090);
livestchat.connect( { onmessage:onChatMessage,onopen:onChatOpenConnect } );
var user_uid = <?php echo $user->user_uid;?>;
var user_knick = '<?php echo $user->user_id;?>';
var user_level = <?php echo $user->user_level;?>;
var user_id = '<?php echo $user->user_id;?>';
var user_email = '<?php echo $user->user_email;?>';
var user_session = '<?php echo $user->user_session; ?>';
var user_quest = null;
var user_step = null;
var conn_location = '<?php echo $_SERVER["REMOTE_ADDR"]; ?>';
var conn_info = '<?php echo $_SERVER["HTTP_USER_AGENT"]; ?>';
var quiz_Sender = false;
var session_id = '';
var current_quiz = null;
var current_step = null;

//onYouTubeIframeAPIReady();
$(document).ready(function(){
  chatControl();
});

checkVideoPlay();

function chatControl(){
  $('body').on('DOMSubtreeModified','#id-chat-history',function(e){
    $('#id-chat-history').scrollTop($('#id-chat-history')[0].scrollHeight);
  })
}

var player = null;
var current_video_id = '';
function onYouTubeIframeAPIReady(e, opt) {
    var auto_start = false;
    var video_id = '';
    if( opt ){
      auto_start = true;
      video_id = getYoutubeVideoId(opt.video_url);
      current_video_id = video_id;
    }
    <?php
    if( $step ){
      echo 'auto_start = true;';
      echo 'video_id = getYoutubeVideoId("'.$step->step_video.'");';
      echo 'current_video_id = video_id;';
      echo 'current_step = '.$step->step_uid.';';
    }
  ?>
    if( player == null ){
      player = new YT.Player('id-video-player'
        , {
          height: '100%'
          ,width: '100%'
          ,videoId: ''
          ,playerVars:{autoplay:true}
          ,events:{
            'onReady':function(e){
              if(auto_start){
                //player.clearVideo();
                //player.loadVideoById(video_id);
                //$('#id_btn_start').hide();
              }
            }
            ,'onStateChange':function(e){
              //console.log(e.data + ' / ' + YT.PlayerState.PLAYING);

              if(e.data == 0 || e.data == -1){
                //console.log('getPlayTimer');
                //getPlayTime();
                //console.log('aaaaa ' + YT.PlayerState.ENDED);
              }


              if (e.data == 1){
                //if(playTimer != null){
                //console.log('playing');
                  //clearPlayTimer();
                  //alert(video_id);
                //}
              }
              //console.log( 'data = ' + e.data);
            }
          }
        }
      );
      //console.log( player );
  }
}

var playTimer = null;
var _interval = 0;
function getPlayTime(){
  //_interval = 0;
  if(playTimer) return;
  playTimer = setInterval( function(){
    //var _sub = player.getCurrentTime() - player.getDuration();
    //console.log(player.getCurrentTime() + '/' + player.getDuration() + ' = ' + _sub);
    _interval++;
    //console.log('i = ' + _interval );
  },1000);
}

function clearPlayTimer(){
  //console.log('clearPlayTimer');
  //_interval = 0;
  clearInterval(playTimer);
  playTimer = null;
  video_id = '';
}

function startVideo(){
  //alert(player + ':' + current_video_id);
  if(player && current_video_id){
    //player.playVideo();
    $('#id_btn_start').hide();
    player.loadVideoById(current_video_id);
  }
}


</script>
<?php
// Chatroll Single Sign-On (SSO) Parameters
$uid = $user->user_uid;                   // Current user id
$uname = $user->user_knick;            // Current user name
$ulink = '';   // Current user profile URL (leave blank for none)
$upic = '';                 // Current user profile picture URL (leave blank for none)
$ismod = 0;                 // Is current user a moderator?
$sig = md5($uid . $uname . $ismod . 'f4apecgpsomc46ru');
$ssoParams = '&uid=' . $uid . "&uname=" . urlencode($uname) . "&ulink=" . urlencode($ulink) . "&upic=" . urlencode($upic) . "&ismod=" . $ismod . "&sig=" . $sig;
?>

<main role="main" id="id-video-container" class="video-container">
  <div class="video-container">
    <div id="id-video-player"></div>
    <div style="position:absolute;left:0px;top:0px;width:100%;height:100%;background-color:rgba(0,0,0,0);">
      <div id="id_btn_start">
        <div class="btn_start"><button type="button" class="btn btn-primary btn_start" onclick="startVideo()">Play</button></div>
      </div>
    </div>
  </div>
  <div id="id-quest-container" class="quest-container">
    <div id="id-quest-box" class="quest-box">
      <ul>
        <li id="id-count-box" class="count-box">
          <img id="id-count-0-0" src="/images/count_0_per_0.png" />
          <img id="id-count-1-5" src="/images/count_1_per_5.png" />
          <img id="id-count-2-5" src="/images/count_2_per_5.png" />
          <img id="id-count-3-5" src="/images/count_3_per_5.png" />
          <img id="id-count-4-5" src="/images/count_4_per_5.png" />
          <img id="id-count-5-5" src="/images/count_5_per_5.png" />
          <img id="id-count-1-3" src="/images/count_1_per_3.png" />
          <img id="id-count-2-3" src="/images/count_2_per_3.png" />
          <img id="id-count-3-3" src="/images/count_3_per_3.png" />
        </li>
      </ul>
      <ul id="id-quest-list-box">
        <li class="quiz-title"></li>
        <li class="quiz-view"></li>
      </ul>
    </div>
    <div id="id-result-box" class="quest-box result-box">
      <ul>
        <li class="correct-box"></li>
        <li class="user-count-box">
          <span class="user_count"></span>
        </li>
      </ul>
      <ul id="id-result-list-box">
        <li class="result-title"></li>
        <li class="result-view"></li>
      </ul>
    </div>
  </div>
  <div id="id-step-result-container" class="quest-container" style="background-image:none;">
    <div id="id-step-result-box" class="quest-box">
      <ul>
        <li></li>
      </ul>
      <ul id="id-step-result-list-box">
        <li class="result-title">결과</li>
        <li class="result-view"></li>
        <li class="" style="text-align:center;padding:10px;">
          <button tyle="button" class="btn btn-primary" onclick="closeStepResult(event)">닫기</button>
        </li>
      </ul>
      <div class="result-logo-container">
        <img src="/images/login_logo.png" width="100%" />
      </div>
    </div>
  </div>
</main>


<script>
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
</script>


<div id="id-chat-container" class="chat-container">
  <div class="chat-header">
    <p>Duvie (<span class="user_count"></span>)<br /><?php echo $user->user_id; ?>님 <a href="/proc/logout.php">[로그아웃]<script type="text/javascript">var _id='<?php echo $user->user_id; ?>';</script></a></p>
  </div>
  <div id="id-chat-history" class="chat-history" style="display:none;">
    <p id="id-msg-admin-notice" class='msg_header'>
      <?php if( $ready_notice ){ echo $ready_notice->content; } ?>
    </p>
  </div>
  <div class="chat-input" style="display:none;">
    <textarea name="chat_message" placeholder="<?php echo $user->user_knick; ?>(으)로 채팅하기." onkeyup="onChatSend(event)" rows="1"></textarea>
  </div>
</div>



<?php
//user_level 이 10 인 사람은 관리자 화면 볼수있다.
if( $user->user_level == 10 ){
  $step_list = $mpark->listStep( null );
?>
      <div id="id-admin-controller" class="admin-controller">
        <div class="row admin-menu-navi">
          <div class="col-md-12">
            [<?php echo $user->user_id; ?>]<br />
            <a href="./admin/pages/dashboard.php" target="_blank">관리자페이지</a>
            &nbsp;&nbsp;
            <?php
              if( $ready_status->logable_level > 0 ){
                echo '<a href="javascript:;" onclick="changeLoginLevel(0)">리허설모드종료</a>';
              }else{
                echo '<a href="javascript:;" onclick="changeLoginLevel(9)">리허설모드시작</a>';
              }
            ?>
            &nbsp;&nbsp;
            <a href="javascript:;" onclick="sendUserRefresh()" style="color:red;">R</a>
            &nbsp;&nbsp;
            <a href="javascript:;" onclick="sendUserLogout()" style="color:red;">K</a>
          </div>
        </div>
        <div class="row admin-step-control">
          <div class="col-md-12">
            <div class="form-group">
              <label for="id_step_select">방송차시선택</label>
              <select id="id_step_select" name="live_step" class="form-control" onchange="adminSelectStep(event)">
                <option value="0">차시를 선택해주세요.</option>
                <?php
                foreach($step_list as $step) {
                ?>
                <option value="<?php echo $step->step_uid; ?>">
                  <?php echo $step->step_title; ?>
                </option>
                <?php
                }
                ?>
              </select>
            </div>
            <div class="form-group" style="text-align:right;">
              <button type="button" class="btn btn-success" onclick="onAir(event)">방송시작</button>
              <button type="button" class="btn btn-warning" onclick="offAir(event)">방송종료</button>
              <button type="button" class="btn btn-primary" onclick="openStepResult(event)">차시결과</button>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="id_step_quiz">문항선택</label>
              <span style="color: #037afb;" onclick="reloadQuiz(event)">
                <svg class="bi bi-arrow-repeat" width="25" height="25" viewBox="0 0 20 23" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M4 9.5a.5.5 0 00-.5.5 6.5 6.5 0 0012.13 3.25.5.5 0 00-.866-.5A5.5 5.5 0 014.5 10a.5.5 0 00-.5-.5z" clip-rule="evenodd"></path>
                  <path fill-rule="evenodd" d="M4.354 9.146a.5.5 0 00-.708 0l-2 2a.5.5 0 00.708.708L4 10.207l1.646 1.647a.5.5 0 00.708-.708l-2-2zM15.947 10.5a.5.5 0 00.5-.5 6.5 6.5 0 00-12.13-3.25.5.5 0 10.866.5A5.5 5.5 0 0115.448 10a.5.5 0 00.5.5z" clip-rule="evenodd"></path>
                  <path fill-rule="evenodd" d="M18.354 8.146a.5.5 0 00-.708 0L16 9.793l-1.646-1.647a.5.5 0 00-.708.708l2 2a.5.5 0 00.708 0l2-2a.5.5 0 000-.708z" clip-rule="evenodd"></path>
                </svg>
              </span>
              <select id="id_step_quiz" name="live_quiz" class="form-control" onchange="adminSelectQuiz(event)">
                <option value=""></option>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div id="id-quiz-view">
            </div>
            <div id="id-before-quiz"></div>
          </div>
        </div>
      </div>
<?php
}
?>
















<!-- 사용자 추가 팝업 -->
<div id="popup_user_regist" class="popup" title="회원가입">

  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="id_user_id">* 로그인 아이디</label>
        <div class="input-group">
          <input type="text" id="id_user_id" name="user_id" class="form-control" onkeydown="changedUserId(event)" onchange="changedUserId(event)" placeholder="아이디를 입력해주세요." style="-webkit-ime-mode:disabled;-moz-ime-mode:disabled;-ms-ime-mode:disabled;ime-mode:disabled;" />
          <div class="input-group-append">
            <button type="button" class="btn btn-primary" onclick="checkOverlapId()">아이디중복확인</button>
          </div>
        </div>
        <div id="id_check_overlap_msg_id">※아이디 중복 체크를 해주세요.</div>
      </div>
    </div>
  </div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="id_user_pwd">* 비밀번호</label>
      <input type="password" id="id_user_pwd" name="user_pwd" class="form-control" placeholder="" style="-webkit-ime-mode:disabled;-moz-ime-mode:disabled;-ms-ime-mode:disabled;ime-mode:disabled;" />
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="id_user_pwd2">* 비밀번호확인</label>
      <input type="password" id="id_user_pwd2" name="user_pwd2" class="form-control" placeholder="" style="-webkit-ime-mode:disabled;-moz-ime-mode:disabled;-ms-ime-mode:disabled;ime-mode:disabled;" />
    </div>
  </div>
  <div>※거래처코드를 아이디로, 영업사원 사번을 비밀번호로 한다</div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="id_user_cscode">* 거래처코드</label>
      <input type="text" id="id_user_cscode" name="user_cscode" class="form-control" placeholder="" />
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="id_user_hospital">* 거래처명</label>
      <input type="text" id="id_user_hospital" name="user_hospital" class="form-control" placeholder="" />
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="id_user_mrname">* 담당MR명</label>
      <input type="text" id="id_user_mrname" name="user_mrname" class="form-control" placeholder="" />
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="checkbox-container">
      <input type="checkbox" id="id-agree" name="agree" value="Y" onclick="onClickAgree(event)">
      <label for="id-agree">개인정보 수집 및 이용 동의(필수)</label>
    </div>
    <textarea readonly rows="5" style="width:100%;font-size:12px;">개인정보 수집 및 이용
1. 개인정보의 수집 및 이용 목적 : 퀴즈쇼 참가와 기타문의에 대한 답변
2. 수집하려는 개인정보 항목 : 이름, 전화번호, 아이디, 이메일, 지역, 병원명
3. 개인벙보의 보유 및 이용기간 : 1년
4. 동의를 거부할 수 있으며, 동의 거부시 퀴즈쇼 참가가 제한됩니다.

개인정보의 처리위탁
회사는 서비스 향상을 위해서 개인정보처리를 위탁하고 있으며, 관계 법령에 따라 위탁계약 시 개인정보가 안전하게 관리될
수 있도록 필요한 사항을 규정하고 있습니다. 회사의 개인정보 위탁처리 기관 및 위탁업무 내용은 다음과 같습니다.
■ 수탁업체 : (주)엔에이치엔에이스
■ 위탁업무 내용 : 로그분석 서비스
■ 개인정보 보유 및 이용기간 : 회원 탈퇴시 혹은 위탁계약 종료시</textarea>



  </div>
</div>
<div class="popup-footer">
  <button type="button" class="btn btn-primary" onclick="submit_add_user()">가입하기</button>
  <button type="button" class="btn btn-secondary" onclick="closePopup('#popup_user_regist')">닫기</button>
</div>
</div>
<script>
function onClickAgree( e ){
  console.log( $(e.target).prop('checked') );
}
</script>




<div id="popup_user_find" class="popup" title="비밀번호찾기">

<div class="row">
<div class="col-md-12">
  <div class="form-group">
	<label for="id_user_id">* 로그인 아이디</label>
	<div class="input-group">
	  <input type="text" id="id_user_id" name="user_id" class="form-control" onkeydown="changedUserId(event)" onchange="changedUserId(event)" placeholder="아이디를 입력해주세요." style="-webkit-ime-mode:disabled;-moz-ime-mode:disabled;-ms-ime-mode:disabled;ime-mode:disabled;" />
	</div>
  </div>
</div>
</div>

<div class="row">
  <div class="col-md-6">
	<div class="form-group">
	  <label for="id_user_mrname">* 담당MR명</label>
	  <input type="text" id="id_user_mrname" name="user_mrname" class="form-control" placeholder="" />
	</div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="id_user_pwd">* 비밀번호</label>
      <input type="password" id="id_user_pwd" name="user_pwd" class="form-control" placeholder="" style="-webkit-ime-mode:disabled;-moz-ime-mode:disabled;-ms-ime-mode:disabled;ime-mode:disabled;" />
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="id_user_pwd2">* 비밀번호확인</label>
      <input type="password" id="id_user_pwd2" name="user_pwd2" class="form-control" placeholder="" style="-webkit-ime-mode:disabled;-moz-ime-mode:disabled;-ms-ime-mode:disabled;ime-mode:disabled;" />
    </div>
  </div>
</div>

<div class="popup-footer">
  <button type="button" class="btn btn-primary" onclick="submit_find_user()">변경하기</button>
  <button type="button" class="btn btn-secondary" onclick="closePopup('#popup_user_find')">닫기</button>
</div>
</div>

<!-- AceCounter Log Gathering Script V.8.0.AMZ2019080601 -->
<script language='javascript'>
	var _AceGID=(function(){var Inf=['gtp18.acecounter.com','8080','AH6A44403282077','AW','1','NaPm,Ncisy','ALL','0']; var _CI=(!_AceGID)?[]:_AceGID.val;var _N=0;var _T=new Image(0,0);if(_CI.join('.').indexOf(Inf[3])<0){ _T.src ="https://"+ Inf[0] +'/?cookie'; _CI.push(Inf);  _N=_CI.length; } return {o: _N,val:_CI}; })();
	var _AceCounter=(function(){var G=_AceGID;var _sc=document.createElement('script');var _sm=document.getElementsByTagName('script')[0];if(G.o!=0){var _A=G.val[G.o-1];var _G=(_A[0]).substr(0,_A[0].indexOf('.'));var _C=(_A[7]!='0')?(_A[2]):_A[3];var _U=(_A[5]).replace(/\,/g,'_');_sc.src='https:'+'//cr.acecounter.com/Web/AceCounter_'+_C+'.js?gc='+_A[2]+'&py='+_A[4]+'&gd='+_G+'&gp='+_A[1]+'&up='+_U+'&rd='+(new Date().getTime());_sm.parentNode.insertBefore(_sc,_sm);return _sc.src;}})();
</script>
<noscript><img src='https://gtp18.acecounter.com:8080/?uid=AH6A44403282077&je=n&' border='0' width='0' height='0' alt=''></noscript>	
<!-- AceCounter Log Gathering Script End -->

</body>
</html>
