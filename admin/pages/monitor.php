<?php
session_start();
require_once("../conf/config.php");


require_once("../../conf/dbconn.php");
require_once("../../class/class.mpark.php");
require_once("../../class/class.mparkquiz.php");
require_once("../../class/class.mparkquizview.php");
require_once("../../class/class.mparkuser.php");
require_once("../../class/class.mparkstep.php");
require_once("../../class/class.mparksearch.php");

$user = new Mparkuser( null );
$user->setFromSession();
if( !$user->user_uid ){
  header('Location:./');
}

include_once("../include/header.php");
?>
<script src="../../js/livest.websocket.js?v=<?php echo time(); ?>"></script>
<script src="../../admin/js/localdb.min.js?v=<?php echo time(); ?>"></script>
<style>
#id-start-process {
  display: none;
}
#id-stop-process {
  display: none;
}

#id-user-answer p,#id-user-log p {
  margin: 0px;
  padding: 2px;
}
#id-user-answer,#id-user-log {
  height:100%;
  max-height:450px;
  overflow:auto;
}
</style>
<script>
//console.log( db );
//{user_uid:user_uid, step_uid:user_quest.step_uid,quiz_uid:user_quest.quiz_uid,view_uid:parseInt( f.data('uid') ),answer_time:answer_time,user_id:user_id }
$(document).ready(function(){

});

var _current_quiz = null;
var _answer_content = [];
var _answer_count = 0;
var _user_count = 0;
function getMessageData( json ){
  console.log( json );
  if( json.cmd == 'system_msg'){
    switch(json.msg){
      case 'user_count':
        showUserCount(json.user_count);
        break;
      case 'answer':
        addAnswerData( json );
        break;
      case 'quiz':
        _current_quiz = json.quiz;
        var html = '';
        html += '<h4>' + json.quiz.quest + '</h4>';
        html += '<div style="padding:10px;">';
        for( var i = 0 ; i < json.quiz.view_list.length ; i++ ){
          html += '<p>' + json.quiz.view_list[i].seq_no + '. ' + json.quiz.view_list[i].view_content;
          if( json.quiz.view_list[i].view_uid == json.quiz.correct_uid ){
            html += ' (정답)';
          }
          html += ' -> <span id="id-view-' + json.quiz.view_list[i].view_uid + '">0</span>';
          html +=  '</p>';
          _answer_content[ json.quiz.view_list[i].view_uid ] = { count:0, view_content:json.quiz.view_list[i].view_content };
        }
        html += '</div>';
        $('#id-quiz-quest').html( html );
        $('#id-user-answer').html( '' );
        _answer_count = 0;
        showAnswerCount(_answer_count);
        break;
      case 'quiz_close':
        $('#id-quiz-quest').text( '대기중...' );
        break;
      case 'hello':
        addUserData( json );
        break;
      case 'user_disconnected':
        showUserCount(json.user_count);
        break;
    }
  }
}

function addUserData(json){
  _user_count++;
  var html = '<p>';
  html += '[' + _user_count + '] ';
  html += json.user_knick + ' ( ' + json.user_id + ' ) ';
  html += ' - ' + json.log_type;
  html += '</p>';
  $('#id-user-log').prepend(html);
}

function addAnswerData( json ){
  _answer_count++;
  var html = '<p>';
  html += '[' + _answer_count + '] ';
  html += json.user_knick + ' ( ' + json.user_id + ' ) : ';
  html += _answer_content[ json.view_uid ].view_content;
  _answer_content[ json.view_uid ].count++;
  html += '</p>';
  $('#id-user-answer').prepend( html );
  showAnswerViewCount( json.view_uid, _answer_content[ json.view_uid ].count);
  showAnswerCount(_answer_count);
}

function showAnswerViewCount( view_uid, cnt ){
  $('#id-view-' + view_uid).text( addCommas(cnt) );
}

function onMessage( e ){
  getMessageData( JSON.parse( e.data ) );
}

function onOpenConnect( e ){
  //livestsocket.send( 'hello|' + user_knick + '|' + user_uid + '|' + user_id + '|' );
  livestsocket.send( { cmd:'system_msg', msg:'hello', uid:user_uid, user_knick:user_knick, user_level:10,conn_location:conn_location,conn_info:conn_info,user_session:user_session,user_no:user_uid,user_id:user_id,user_type:10,step_uid:0 } );
}

function showAnswerCount( n ){
  $('#id-answer-count').text( addCommas(n) );
}

function showUserCount( n ){
  $('#id-user-count').text( addCommas(n) );
}

var livestsocket = new LivestWebsocket('175.123.253.156',18080);
livestsocket.connect( { onmessage:onMessage,onopen:onOpenConnect } );

var user_uid = <?php echo $user->user_uid;?>;
var user_knick = '<?php echo $user->user_knick;?>';
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

</script>

<div class="row">
    <div class="col-md-8">
      <div id="id-quiz-quest">
      </div>
      <h4>참가자 응답(<span id="id-answer-count"></span>)</h4>
      <div id="id-user-answer">
      </div>
    </div>
    <div class="col-md-4">
      <h4>참가자 접속(<span id="id-user-count"></span>)</h4>
      <div id="id-user-log">
      </div>
    </div>
</div>

<?php
 include_once("../include/footer.php");
?>
