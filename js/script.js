//ajax 요청
function jsonPost( param ){
  console.log(param);
  $.ajax({
    url:param.url
    ,type:'post'
    ,dataType:'json'
    ,contentType:'application/json'
    ,cache:false
    ,data:JSON.stringify( param.data )
    ,success:function( json ){
      if( param.success != null ){
        param.success.call( null, json );
      }else{
        alert('성공')
      }
    }
    ,error:function(){
      if( param.error != null ){
        param.error.call( null );
      }else{
        alert('실패');
      }
    }
  });
}


//회원가입 팝업 오픈
function openUserAddPopup( json ){
  $('#popup_user_regist').dialog({
    width:500
    ,modal:true
    ,open:function(){
      if( json ){
        $('#popup_user_regist').find('input[name="user_name"]').val(json.user_name).prop('readonly',true);
        $('#popup_user_regist').find('input[name="user_phone"]').val(json.user_phone).prop('readonly',true);
      }else{
        clearPopupForm('#popup_user_regist');
      }
      $('.ui-dialog-titlebar-close').remove();
    }
  });
}

// 아이디 중복 확인
function checkOverlapId(){
  var f = $('#popup_user_regist');
  var data = new Object();
  data.type = 'user';
  data.mode = 'search_id';
  data.user_id = f.find('input[name="user_id"]').val();

  jsonPost({
    url:'./admin/proc/api.php'
    ,data:data
    ,success:function(json){
      //console.log(json);
      if( json.message ){
        check_id_completed = false;
        $('#id_check_overlap_msg_id').html( '<p style="color:#000;">' + json.message + '</p>' );
      }else if( json.count > 0 ){
        check_id_completed = false;
        $('#id_check_overlap_msg_id').html( '<p style="color:red;">이미 사용중인 아이디입니다. 다른 아이디를 입력해주세요.</p>' );
      }else{
        check_id_completed = true;
        $('#id_check_overlap_msg_id').html( '<p style="color:blue;">사용 가능한 아이디입니다.</p>' );
      }

      //refresh();
    }
  });
}


// 회원가입하기
function submit_add_user(){

  var f = $('#popup_user_regist');
  var data = new Object();
  data.type = 'user';
  data.mode = 'insert';
  data.user_id = f.find('input[name="user_id"]').val().trim();
  data.user_pwd = f.find('input[name="user_pwd"]').val().trim();
  data.user_pwd2 = f.find('input[name="user_pwd2"]').val().trim();
  data.user_cscode = f.find('input[name="user_cscode"]').val().trim();
  data.user_hospital = f.find('input[name="user_hospital"]').val().trim();
  data.user_mrname = f.find('input[name="user_mrname"]').val().trim();

  if( !$('#id-agree').prop('checked') ){
    alert('개인정보 수집 및 이용에 동의해야합니다.');
    return false;
  }

  if( !data.user_id ){
    alert('아이디를 입력해 주세요.');
    return false;
  }

  if( !check_id_completed ){
    alert('아이디 중복확인을 해주세요.');
    return false;
  }

  if( !data.user_pwd ){
    alert('비밀번호를 입력해 주세요.');
    return false;
  }else{
    if( data.user_pwd != data.user_pwd2 ){
      alert('비밀번호 확인이 일치하지 않습니다.');
      return false;
    }
  }

  if( !data.user_cscode ){
    alert('거래처코드를 입력해 주세요.');
    return false;
  }

  if( !data.user_hospital ){
    alert('거래처명을 입력해 주세요.');
    return false;
  }

  if( !data.user_mrname ){
    alert('담당MR명을 입력해 주세요.');
    return false;
  }

  



  jsonPost({
    url:'./admin/proc/api.php'
    ,data:data
    ,success:function(json){
      alert(json.message);
      refresh();
    }
  });
}

//비밀번호 찾기 팝업 오픈
function openUserFindPopup( json ){
  $('#popup_user_find').dialog({
    width:500
    ,modal:true
    ,open:function(){
      if( json ){
        $('#popup_user_find').find('input[name="user_name"]').val(json.user_name).prop('readonly',true);
        $('#popup_user_find').find('input[name="user_phone"]').val(json.user_phone).prop('readonly',true);
      }else{
        clearPopupForm('#popup_user_find');
      }
      $('.ui-dialog-titlebar-close').remove();
    }
  });
}

// 비밀번호찾기
function submit_find_user(){

  var f = $('#popup_user_find');
  var data = new Object();
  data.type = 'user';
  data.mode = 'find';
  data.user_id = f.find('input[name="user_id"]').val().trim();
  data.user_mrname = f.find('input[name="user_mrname"]').val().trim();
  data.user_pwd = f.find('input[name="user_pwd"]').val().trim();
  data.user_pwd2 = f.find('input[name="user_pwd2"]').val().trim();

  if( !data.user_id ){
    alert('아이디를 입력해 주세요.');
    return false;
  }

  if( !data.user_mrname ){
    alert('담당MR명을 입력해 주세요.');
    return false;
  }

  if( !data.user_pwd ){
    alert('비밀번호를 입력해 주세요.');
    return false;
  }else{
    if( data.user_pwd != data.user_pwd2 ){
      alert('비밀번호 확인이 일치하지 않습니다.');
      return false;
    }
  }

  jsonPost({
    url:'./admin/proc/api.php'
    ,data:data
    ,success:function(json){
      alert(json.message);
      refresh();
    }
  });
}

//로그인
function login(){
  //location.href = '/front/pages/live.php';
  var data = new Object();
  data.user_id = $('#id-user-login').find('input[name="user_id"]').val().trim();
  data.user_pwd = $('#id-user-login').find('input[name="user_pwd"]').val().trim();

  jsonPost({
    url:'./proc/login.php'
    ,data:data
    ,success:function(json){

      if( json.message == 'success' ){
        refresh('./live.php');
      }else{
        alert( json.message );
      }
    }
  });
}








//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
















function jumpUrl( u, p ){
  if(u){
    location.href = u;
  }
}

function refresh( u ){
  if( u ){
    location.replace(u);
  }else{
    location.reload();
  }
}

function closePopup( id ){
  $(id).dialog('close');
}


function onLoginKeyUp( e ){
  if( e.keyCode == 13 ){
    login();
    return false;
  }
}



var _quiz_view_timer = null;
function countSeconds( g ){
  var s = g + 10;
  var t = g;
  _quiz_view_timer = setInterval( function(){
    if( t <= 1 ){
      clearInterval(_quiz_view_timer);
      disableQuiz();
      countQuizClose();
      return;
    }

    s--;
    if( s == t ){
      $('#id-count-' + t + '-' + g ).show();
    }
    if( s < t ){
      $('#id-count-' + t + '-' + g ).hide();
      t--;
      $('#id-count-' + t + '-' + g ).show();
    }

  }, 1000);
}

var _quiz_timer = null;
function countQuizClose(){
  var t = 0;
  closeQuiz();
  _quiz_timer = setInterval( function(){
    t++;
    if( t == 2 ){
      if( quiz_Sender == true ){
        quiz_Sender = false;
        sendQuizResult();
        clearInterval(_quiz_timer);
        _quiz_timer = null;
        return;
      }
    }
  }, 1000);
}

var _quiz_result_timer = null;
var _quiz_result_timer_count = 0;
function openQuizResultTimer( json ){
  //console.log(json);
  openQuizResultJson( json );
  _quiz_result_timer_count = 0;
  _quiz_result_timer = setInterval(function(){
    if(_quiz_result_timer_count >= 10 ){
      clearInterval(_quiz_result_timer);
      _quiz_result_timer_count = null
      closeQuizResult();
      return;
    }
    _quiz_result_timer_count++;
  },1000);
}

function showQuiz( json ){
  if( _quiz_view_timer ){
    clearInterval(_quiz_view_timer);
  }
  if( _quiz_timer ){
    clearInterval(_quiz_timer);
  }
  if( _quiz_result_timer ){
    clearInterval(_quiz_result_timer);
  }

  user_quest = json;

  var html = '<ul>';
  html += '<li class="quiz-title">' + json.quest + '</li>';
  for( var i = 0 ; i < json.view_list.length ; i++ ){
    html += '<li class="view-content" data-step="' + json.step_uid + '" data-quiz="' + json.quiz_uid + '" data-uid="' + json.view_list[i].view_uid + '" onclick="selectView(event)">' + (i+1) + '. ' + json.view_list[i].view_content +'</li>';
  }
  html += '</ul>';
  openQuiz();
  $('#id-quest-list-box').html( html );
  sendQuestLog({type:'log',mode:'quest',step_uid:user_quest.step_uid,quiz_uid:user_quest.quiz_uid,user_uid:user_uid})
  setPopupPosition();
  answerTimerStart();
  countSeconds( json.wait_count );
}

function sendQuestLog( obj ){
  jsonPost({
    data:obj
    ,url:'/admin/proc/api.php'
    ,success:function(json){

    }
  });
}

function showStep( json ){
  user_step = json;
  if( user_step == 'off' ){
    //$('#id-video-player').attr('src','');
    player.stopVideo();
    player.clearVideo();
    $('#id_btn_start').show();
  }else{
    if( user_step.step_video.indexOf('youtube.com') >= 0 || user_step.step_video.indexOf('youtu.be') >= 0 ){
      if(player != null){
        //getPlayTime();
        current_video_id = getYoutubeVideoId( user_step.step_video );
        player.loadVideoById(current_video_id);
        try{$('#id_btn_start').hide();}catch(e){};
      }else{
        onYouTubeIframeAPIReady(null,{video_url:user_step.step_video});
        current_video_id = getYoutubeVideoId( user_step.step_video );
        try{$('#id_btn_start').hide();}catch(e){};
      }
    }else{

    }

  }
}

function getYoutubeVideoId( url ){
  var arr = url.split('/');
  return arr[arr.length - 1];
}

function selectView( e ){
  var f = $(e.target);
  f.addClass('view-content-active');
  var data = {
    cmd:'system_msg'
    , msg:'answer'
    , user_uid:user_uid
    , step_uid:user_quest.step_uid
    ,user_knick:user_knick
    ,quiz_uid:user_quest.quiz_uid
    ,view_uid:parseInt( f.data('uid') )
    ,answer_time:answer_time
    ,user_id:user_id
    ,user_session:user_session
    ,session_id:session_id
   };
  livestsocket.send( data );
  //testSend( data );
  disableQuiz();
}

var _test_send_count = 0;
var _test_timer = null;
function testSend( data ){
  _test_send_count = 0;
  _test_timer = setInterval(function(){
    if(_test_send_count > 10){
      clearInterval(_test_timer);
      _test_timer = null;
      return;
    }else{
      livestsocket.send( data );
      _test_send_count++;
    }
  },10);
}

function disableQuiz(){
  $('#id-quest-list-box li.view-content').each(function(index,ele){
    $(this).prop('onclick',null).off('click');
  });
  answerTimerStop();
}

function getMessageTypeClass( cmd ){
  switch( cmd ){
    case 'msg':
      return 'user_msg';
    case 'admin_msg':
      return 'user_msg admin_msg';
    case 'system_msg':
      return 'user_msg system_msg';
    case 'notice_msg':
      return 'user_msg notice_msg';
    default :
      return 'user_msg';
  }
}

function getIconTypeHTML( cmd ){
  switch( cmd ){
    case 'msg':
      //return '<span class="icon-box"><svg class="bi bi-person-fill" width="21" height="21" viewBox="0 0 19 21" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 16s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H5zm5-6a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg></span>';
      return '';
    case 'admin_msg':
      return '<span class="icon-box"><svg class="bi bi-person-fill" width="21" height="21" viewBox="0 0 19 21" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 16s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H5zm5-6a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg></span>';
    case 'system_msg':
      return '';
    case 'notice_msg':
      return '';
    default :
      return '';
      //return '<span class="icon-box"><svg class="bi bi-person-fill" width="21" height="21" viewBox="0 0 19 21" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 16s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H5zm5-6a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg></span>';
  }
}

function onChatSend( e ) {
  if( e.keyCode == 13 ){
    var msg = $(e.target).val().trim();
    $(e.target).val('');
    if(!msg){
      return false;
    }
    if(user_level == 10 && msg.indexOf('공지:') ==0 ){
      livestchat.send( { cmd:'admin_notice', msg:msg.split('공지:')[1], user_uid:user_uid, user_knick:user_knick, user_level:user_level } );
    }else{
      if( msg.length > 50 ){
        alert('한번에 전송할 수 있는 최대 글자수는 50자 입니다.');
      }else if(checkWording(msg)){
        livestchat.send( { cmd:(user_level == 10)?'admin_msg':'msg', msg:msg, user_uid:user_uid, user_knick:user_knick, user_level:user_level } );
      }else{
        alert('허용되지 않는 단어가 포함된 메시지입니다.');
      }
    }
  }
}

function checkWording(str){
  var result = true;
  var arr = ['강간','개넌','개년','개놈','개뇬','개새끼','게세끼','꼬추','늬미','니기미','니미','닝기리','색스','색쑤','색쓰','섹수','섹스','섹쑤','섹쓰','시발','시팔','쌍놈','쌍년','쌕수','쌕스','쌕쑤','쌕쓰','썅놈','썅년','쎅수','쎅스','쎅쑤','쎅쓰','씨발','씹','야동','애자','앰창','에자','엠창','염병','옘병','유방','음경','음란','음모','음부','잠지','조까','조또','족같은','좆','창녀','챵녀','클리토리스','페니스','화냥년','asshole','bastard','bitch','cock','damn','fuck','jiral','jot','penis','pussy','shit','sibal','suck'];
  str = str.toLowerCase().trim();
  for( var value in arr ){
    if( str.indexOf( arr[value] ) >= 0 ){
      result = false;
      break;
    }
  }
  return result;
}

$(document).ready(function(e){
  resizeWindowHandler(e);
  $(window).resize(resizeWindowHandler);
});

function resizeWindowHandler( e ){
  setPopupPosition();
}


function setPopupPosition(){
  //console.log(e);
  //console.log( $(document).width() );
  var window_height = window.innerHeight;
  var window_width = window.innerWidth;
  //$('#id-video-container').width(window_width);
  //$('#id-video-container').height(window_height);

  var quest_width = 600; //$('#id-quest-box').width();
  var quest_height = 360; //$('#id-quest-box').height();
  var video_height = $('#id-video-container').height();
  var chat_height = Math.min( video_height, window_height ) - 20;
  var chat_width = ($('#id-chat-container').is(':visible'))?$('#id-chat-container').width():0;
  $('#id-chat-container').height( chat_height );

  var quest_left = ( window_width - chat_width - quest_width ) / 2;
  var quest_top = ( chat_height - quest_height ) / 2;
  if( $('#id-admin-controller').length > 0 ){
    var admin_container_width = $('#id-admin-controller').width() + 44;
    if( quest_left < admin_container_width ){
      quest_left = admin_container_width;
      //console.log(quest_left + ' / ' + $('#id-admin-controller').width() + ' = ' + quest_left);
    }
  }
  $('#id-quest-box').css('left', quest_left + 'px');
  $('#id-quest-box').css('top', quest_top + 'px');
  $('#id-result-box').css('left', quest_left + 'px');
  $('#id-result-box').css('top', quest_top + 'px');
  $('#id-step-result-box').css('left', quest_left + 'px');
  $('#id-step-result-box').css('top', quest_top + 'px');
}


function onMessage( e ){
  //console.log(e.data);
  var json = JSON.parse( e.data );
  getChatMessage(json);
  //$('#id-chat-history').append( getChatMessage(json) );
  //setChatOpacity();
}

function onChatMessage( e ){
  //console.log(e.data);
  var json = JSON.parse( e.data );
  $('#id-chat-history').append( getChatMessage(json) );
  //setChatOpacity();
}

function setChatOpacity(){
  var h = $('#id-chat-history').height();
  $('#id-chat-history > p').each( function( idx, ele ){
    var top = $(this).offset().top;
    if( top < (h/2) ){
      $(this).css('opacity',(top/h/2) + 0.3);
    }
    //console.log( $(this).offset().top);
  });
}

function onOpenConnect( e ){
  //livestsocket.send( 'hello|' + user_knick + '|' + user_uid + '|' + user_id + '|' );
  var data = {
    cmd:'system_msg'
    , msg:'hello'
    , user_uid:user_uid
    , user_knick:user_knick
    , user_level:user_level
    , location:conn_location
    , log_type:'connect'
    , client_info:conn_info
    , user_session:user_session
    , user_no:user_uid
    , user_id:user_id
    , user_type:user_level
    , step_uid:(user_step?user_step.step_uid:0)
  };
  livestsocket.send( data );
}

function onChatOpenConnect( e ){
  //livestsocket.send( 'hello|' + user_knick + '|' + user_uid + '|' + user_id + '|' );
  var data = {
    cmd:'system_msg'
    , msg:'hello'
    , user_uid:user_uid
    , user_knick:user_knick
    , user_level:user_level
    , location:conn_location
    , log_type:'connect'
    , client_info:conn_info
    , user_session:user_session
    , user_no:user_uid
    , user_id:user_id
    , user_type:user_level
    , step_uid:(user_step?user_step.step_uid:0)
  };
  livestchat.send( data );
}

var _wait_quiz = 0;
var _wait_quiz_timer = null;
function getChatMessage( json ){
  console.log( json );
  var cmd = json.cmd;
  var msg = json.msg;
  var uid = json.user_uid;
  var knick = json.user_knick;
  var level = json.user_level;
  var msg_html = '';
  switch( cmd ){
    case 'msg':
      msg_html = '<p class="' + getMessageTypeClass(cmd) + '"><span>' + getIconTypeHTML(cmd) + knick + '</span> : ' + msg + '</p>';
      break;
    case 'admin_msg':
      msg_html = '<p class="' + getMessageTypeClass(cmd) + '"><span>' + getIconTypeHTML(cmd) + knick + '</span><br />' + msg + '</p>';
      break;
    case 'admin_notice':
      $('#id-msg-admin-notice').html( msg );
      break;
    case 'system_msg':
      switch(json.msg){
        case 'quiz':
          showQuiz(json.quiz);
          break;
        case 'step':
          showStep( json.step );
          break;
        case 'user_count':
          updateUserCount( json.user_count );
          break;
        case 'step_result':
          showStepResult( json.step_uid );
          break;
        case 'live_ready':
          changeUserLevel( json.user_level );
          break;
        case 'quiz_result':
          openQuizResultTimer(json);
          break;
        case 'page_refresh':
          //console.log('page_refresh');
          if(user_level < 10 ){
            refresh();
          }
          break;
        case 'hello':
          if( json['user_count'] ){
            updateUserCount( json.user_count );
          }
          console.log( json.user_session, user_session, json );
          if( json.user_session == user_session ){
            session_id = json.session_id;
          }else{
            if(json.user_id == 'admin' && user_id == 'admin'){
              kickout('관리자는 한명만 접속해야합니다.',json.session_id, json.user_id);
            }
          }
          break;
        case 'kickout':
          if( json.kick_session_id == session_id ){
            if( json.kick_user_id == user_id && user_level >= 10){
              gotoAdmin( json.message );
            }else{
              logoutUser( json.message );
            }
          }
          break;
        case 'logoutall':
          logoutUser( '관리자가 모든 사용자를 로그아웃했습니다.' );
          break;
      }
  }
  return msg_html;
}



function showStepResult( step_uid ){
  $('#id-step-result-container').show();
  var data = new Object();
  data.type = 'step';
  data.mode = 'winner';
  data.step_uid = step_uid;
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      //console.log( json );
      var html = '';
      for( var i = 0 ; i < json.answer_list.length ; i++ ){
        html += '<p class="result-person">';
        html += ' ' + (i+1) + '. ';
        html += '<svg class="bi bi-person-fill" width="30" height="30" viewBox="0 0 25 25" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 16s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H5zm5-6a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>';
        html += json.answer_list[i].user_knick + '( 정답 수: ' + json.answer_list[i].correct_count +'개 , 시간: ' + getTimeString(json.answer_list[i].total_time) + ' )</p>';
      }
      //console.log( html );
      $('#id-step-result-list-box .result-view').html( html );
      setPopupPosition();
    }
  });
}

function getTimeString( ms ){
  var s = parseInt( ms / 100 );
  var mss = parseInt( ms % 100 );
  var m = 0;
  if( s >= 60 ){
    m = parseInt(s / 60);
    s = s % 60;
  }
  var rst = '';
  if( m ){
    rst += m + '분 ';
  }
  rst += parseInt(s) + '.' + mss + '초';
  return rst;
}

function closeStepResult( e ){
  $('#id-step-result-container').hide();
}

function updateUserCount( count ){
  //console.log( '접속자 = ' + count );
  $('.user_count').text( count );
}

function openQuiz(){
  $('#id-count-box img').hide();
  $('#id-count-0-0').show();
  $('#id-quest-list-box').html('');
  $('#id-quest-container').show();
  $('#id-quest-box').show();
  $('#id-result-box').hide();
  $('#id-step-result-container').hide();
  $('#id-chat-container').hide();
}

function closeQuiz(){
  clearInterval(_quiz_view_timer);
  $('#id-quest-list-box').html('');
  $('#id-quest-box').hide();
}

function openQuizResultJson( json ){
  //console.log(json);
  $('#id-quest-container').show();
  $('#id-result-list-box .result-view').html( '' );
  $('#id-result-box').show();
  $('#id-result-box .correct-box').text( '' );

  var html  = '';
  var html = '<ul>';
  var gap = 100 / json.answer_list.length;
  var h = 100;
  html += '<li class="quiz-title">' + json.quest + '</li>';
  var total_count = 0;
  console.log( json );
  for( var i = 0 ; i < json.answer_list.length ; i++ ){
    h = parseInt(json.answer_list[i].answer_count) / parseInt(json.total) * 100;
	   total_count += parseInt(json.answer_list[i].answer_count);
    if( json.answer_list[i].view_uid == json.answer_list[i].correct_uid ){
      $('#id-result-box .correct-box').text( '정답 : ' + json.answer_list[i].view_content );
      html += '<li class="view-content correct-bar" style="background-size: ' + h + '% 100%" data-step="' + json.step_uid + '" data-quiz="' + json.quiz_uid + '" data-uid="' + json.answer_list[i].view_uid + '" >' + json.answer_list[i].seq_no + '. ' + json.answer_list[i].view_content +'<span> (' + json.answer_list[i].answer_count + ' )</span></li>';
    }else{
      html += '<li class="view-content result-bar" style="background-size: ' + h + '% 100%" data-step="' + json.step_uid + '" data-quiz="' + json.quiz_uid + '" data-uid="' + json.answer_list[i].view_uid + '" >' + json.answer_list[i].seq_no + '. ' + json.answer_list[i].view_content +'<span> (' + json.answer_list[i].answer_count + ' )</span></li>';
    }
  }

  //console.log( html );
  html += '</ul>';
  $('#id-result-list-box .result-view').html( html );
  updateUserCount( total_count );
  setPopupPosition();
}

function openQuizResult(){

  $('#id-result-list-box .result-view').html( '' );
  $('#id-result-box').show();
  $('#id-result-box .correct-box').text( '' );

  var data = new Object();
  data.type = 'quiz_answer';
  data.mode = 'view';
  data.step_uid = user_quest.step_uid;
  data.quiz_uid = user_quest.quiz_uid;

  jsonPost({
    url:"/admin/proc/api.php"
    ,data:data
    ,success:function(json){
      //$('#id-result-list-box .result-title').html( '<p><span class="quest-icon">Q' + json.show_num + '. </span>' + json.quest + '</p>' );
      var html  = '';
      /*
      var gap = 100 / json.answer_list.length;
      var h = 100;
      for( var i = 0 ; i < json.answer_list.length ; i++ ){
        h = 100 - ( json.answer_list[i].answer_count / json.total * 100 );
        if( json.answer_list[i].view_uid == json.correct_uid ){
          $('#id-result-list-box .correct-box').text( json.answer_list[i].view_content );
        }
        html += '<div class="result_view_box" style="width:' + gap + '%;left:' + ( gap * i ) + '%;"><div class=""><p style="height:' + h + '%;"><span>' + json.answer_list[i].answer_count + '</span><span>' + json.answer_list[i].view_content +'</span></p></div></div>';
      }*/
      var html = '<ul>';
      var gap = 100 / json.answer_list.length;
      var h = 100;
      html += '<li class="quiz-title">' + user_quest.quest + '</li>';
      for( var i = 0 ; i < user_quest.view_list.length ; i++ ){
        h = json.answer_list[i].answer_count / json.total * 100;
        if( user_quest.view_list[i].view_uid == user_quest.view_list[i].correct_uid ){
          $('#id-result-box .correct-box').text( '정답 : ' + user_quest.view_list[i].view_content );
          //html += '<li class="view-content correct-bar" style="background-size: ' + h + '% 100%" data-step="' + user_quest.step_uid + '" data-quiz="' + user_quest.quiz_uid + '" data-uid="' + user_quest.view_list[i].view_uid + '" >' + user_quest.view_list[i].view_content +'<span class="result-count">' + json.answer_list[i].answer_count + '</span></li>';
          html += '<li class="view-content correct-bar" style="background-size: ' + h + '% 100%" data-step="' + user_quest.step_uid + '" data-quiz="' + user_quest.quiz_uid + '" data-uid="' + user_quest.view_list[i].view_uid + '" >' + user_quest.view_list[i].seq_no + '. ' + user_quest.view_list[i].view_content +'<span> (' + json.answer_list[i].answer_count + ' )</span></li>';
        }else{
          html += '<li class="view-content result-bar" style="background-size: ' + h + '% 100%" data-step="' + user_quest.step_uid + '" data-quiz="' + user_quest.quiz_uid + '" data-uid="' + user_quest.view_list[i].view_uid + '" >' + user_quest.view_list[i].seq_no + '. ' + user_quest.view_list[i].view_content +'<span> (' + json.answer_list[i].answer_count + ' )</span></li>';
        }
      }
      html += '</ul>';
      $('#id-result-list-box .result-view').html( html );
      setPopupPosition();
    }
  });
}

function closeQuizResult(){
  $('#id-result-box').hide();
  $('#id-quest-container').hide();
  $('#id-chat-container').show();

  var before_quest = '';
  if($('#id_step_quiz').length > 0 ){
    $('#id_step_quiz option').each(function(idx,ele){
      if( user_quest ){
        if( parseInt( $(this).attr('value') ) == user_quest.quiz_uid ){
          before_quest = $(this).text();
  //        $(this).remove();
        }
      }
    });
    if( before_quest ){
      $('#id-quiz-view .admin-quiz-view-list').html('');
      $('#id-before-quiz').html('<p>이전 퀴즈 : ' + before_quest + '</p>');
    }else{
      $('#id-before-quiz').html('');
    }

  }
  user_quest = null;
}


// 사용자 추가 팝업
// 실명인증 사용시
function openUserAdd(){
  var data = new Object();
  data.type = 'kmcis';
  data.mode = 'cert';

  jsonPost({
    url:'/proc/kmcis.php'
    ,data:data
    ,success:function(json){
      $('#id-reqKMCISForm').find('input[name="tr_cert"]').val(json.tr_cert);
      $('#id-reqKMCISForm').find('input[name="tr_url"]').val(json.tr_url);
      $('#id-reqKMCISForm').find('input[name="tr_add"]').val(json.tr_add);
      openKMCISWindow();
    }
    ,fail:function(){
      //console.log( '실명인증 로드 실패');
    }
  });
}

function openUserAddPopup( json ){
  $('#popup_user_regist').dialog({
    width:500
    ,modal:true
    ,open:function(){
      if( json ){
        $('#popup_user_regist').find('input[name="user_name"]').val(json.user_name).prop('readonly',true);
        $('#popup_user_regist').find('input[name="user_phone"]').val(json.user_phone).prop('readonly',true);
      }else{
        clearPopupForm('#popup_user_regist');
      }
      $('.ui-dialog-titlebar-close').remove();
    }
  });
}



// 사용자 수정 팝업
function openUserEdit( user_uid ){
  $('#popup_user_regist').dialog({
    width:500
    ,modal:true
    ,open:function(){
      clearPopupForm('#popup_user_regist');
      var f = $('#popup_user_regist');
      f.find('input[name=""]').val();
    }
  });
}

var check_id_completed = false;
var check_knick_completed = false;




// 별명 중복확인
function checkOverlapKnick(){
  var f = $('#popup_user_regist');
  var data = new Object();
  data.type = 'user';
  data.mode = 'search_knick';
  data.user_knick = f.find('input[name="user_knick"]').val();

  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      if( json.message ){
        $('#id_check_overlap_msg_knick').html( '<p style="color:#000;">' + json.message + '</p>' );
        check_knick_completed = false;
      }else if( json.count > 0 ){
        $('#id_check_overlap_msg_knick').html( '<p style="color:red;">이미 사용중인 별명입니다. 다른 별명을 입력해주세요.</p>' );
        check_knick_completed = false;
      }else{
        $('#id_check_overlap_msg_knick').html( '<p style="color:blue;">사용 가능한 별명입니다.</p>' );
        check_knick_completed = true;
      }
    }
  });
}








function changedUserId(e){
  check_id_completed = false;
  $('#id_check_overlap_msg_id').html('아이디 중복 체크를 해주세요');
}

function changedUserKnick(e){
  check_knick_completed = false;
  $('#id_check_overlap_msg_knick').html('별명 중복 체크를 해주세요.');
}

function clearPopupForm( target ){
  $( target ).find('input').each(function(index,ele){
    $(this).val('');
  });
  $( target ).find('select').each(function(index,ele){
    $(this).val('');
  });
}

var answer_time = 0;
var answer_timer = null;
function answerTimerStart(){
  answer_time = 0;
  answer_timer = setInterval(function(){
    answer_time++;
  }, 10);
}

function answerTimerStop(){
  answer_time = 0;
  clearInterval(answer_timer);
}

function changeUserLevel( level ){
  if( level == 0 && user_level < 10 ){
    alert( '리허설 모드가 종료되었습니다.');
  }else{
    if( user_level < level ){
      refresh('/proc/logout.php');
    }else if( user_level < 10){
      alert( '리허설 모드가 시작되었습니다.');
    }
  }
}

function logoutUser( msg ){
  if( msg ){
    alert( msg );
  }
  refresh('/proc/logout.php');
}

function gotoAdmin( msg ){
  if( msg ){
    alert( msg );
  }
  refresh('/admin/pages/dashboard.php');
}

function checkVideoPlay(){

  if(!!document.createElement('video').canPlayType && !!document.createElement('video').canPlayType('video/mp4;codecs="avc1.42E01E, mp4a40.2"')) {
  //if(Modernizr.video){
    console.log("현재 브라우저는 비디오를 지원합니다")
  }
  else{
    console.log("현재 브라우저는 비디오를 지원하지 않습니다")
  }
}
