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
include_once("../../conf/dbconn.php");
include_once("../../conf/config.php");
include_once("../../include/function.php");
include_once("../../class/class.mpark.php");
include_once("../../class/class.mparkuser.php");

$user = new Mparkuser( null );
$user->setFromSession();
if( !$user->user_uid ){
  header('Location:./');
}
$mpark = new Mpark($dbconn);
$ready_status = $mpark->getReadyStatus('live_ready');
$ready_notice = $mpark->getReadyStatus('live_notice');


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
if( $user->user_level == 10 ){
  $step_list = $mpark->listStep( null );
?>
<div id="id-admin-controller" class="admin-controller">
  <div class="row admin-menu-navi">
    <div class="col-md-12">
      [<?php echo $user->user_id; ?>]<br />
      <a href="/admin/pages/dashboard.php" target="_blank">관리자페이지</a>
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

<?php
include_once('../../include/footer.php');
?>
