function adminSelectStep(e){
  var f = $(e.target);
  var step_uid = parseInt( f.val() );
  var data = new Object();
  data.type = 'step';
  data.mode = 'view';
  data.step_uid = step_uid;
  clearQuizView();
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      current_step = json;
      var html = '<option value="0">퀴즈를 선택해주세요.</option>';
      if( json.quiz_list.length < 1 ){
        html = '<option value="0">설정된 퀴즈가 없습니다.</option>';
      }
      for( var i = 0 ; i < json.quiz_list.length ; i++ ){
        html += '<option value="' + json.quiz_list[i].quiz_uid + '">' + json.quiz_list[i].quest + '</option>';
      }
      $('#id_step_quiz').html( html );
    }
  });
}

function adminSelectQuiz(e){
  var f = $(e.target);
  var quiz_uid = parseInt( f.val() );
  var data = new Object();
  data.type = 'quiz';
  data.mode = 'view';
  data.quiz_uid = quiz_uid;
  clearQuizView();
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      current_quiz = json;
      var html = '<ul class="admin-quiz-view-list">';
      if( json.view_list.length < 1 ){
        html += '<li data-uid="0">보기가 없습니다.</li>';
      }
      for( var i = 0 ; i < json.view_list.length ; i++ ){
        html += '<li data-uid="' + json.view_list[i].view_uid + '" ' + ((json.view_list[i].view_uid == json.view_list[i].correct_uid)?'class="is-correct"':'') + '>';
        html += (i+1) + '. ';
        html += json.view_list[i].view_content + '</li>';
      }
      html += '</ul>';
      if( json.view_list.length > 0 ){
        html += '<ul class="admin-quiz-send"><li><button type="button" class="btn btn-primary" onclick="adminSendQuiz(event)">문제발송</button> <button type="button" class="btn btn-danger" onclick="sendQuizResult()">결과발송</button></li></ul>';
        //html += '<button type="button" class="btn btn-primary" onclick="openQuizWinner(event)">퀴즈정답자</button>';
      }
      $('#id-quiz-view').html( html );
    }
  });
}

function clearQuizView(){
  $('#id-quiz-view').html( '' );
}

function adminSendQuiz(e){
  quiz_Sender = true;
  livestsocket.send( { cmd:'system_msg', msg:'quiz', uid:user_uid, user_knick:user_knick, user_level:user_level, quiz:current_quiz } );
}

function onAir(e){
  if( !confirm('선택하신 방송을 시작하시겠습니까?') ){
    return false;
  }
  var step = current_step;
  if(!current_step){
    alert( '방송을 선택해 주세요.');
    return false;
  }

  if(step && step['quiz_list']){
    delete step['quiz_list'];
  }

  livestsocket.send( { cmd:'system_msg', msg:'step', uid:user_uid, user_knick:user_knick, user_level:user_level, step:step } );
  var data = step;
  data.type = 'step';
  data.mode = 'onair';
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:step
    ,success:function(json){
      //alert(json.message);
    }
  });
}

function sendStepLog( obj ){
  jsonPost({
    data:obj
    ,url:'/admin/proc/api.php'
  });
}

function offAir(e){
  if( !confirm('진행중인 방송을 종료하시겠습니까?') ){
    return false;
  }
  var step = current_step;
  if( step ){
    delete step['quiz_list'];
  }
  livestsocket.send( { cmd:'system_msg', msg:'step', uid:user_uid, user_knick:user_knick, user_level:user_level, step:'off' } );
  var data = null;
  if( !current_step ){
    data = new Object();
    data.step_uid = 0;
  }else{
    data = step;
  }

  data.type = 'step';
  data.mode = 'offair';
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      alert(json.message);
    }
  });
}

function openStepResult(){
  //console.log( current_step.step_uid );
  livestsocket.send( { cmd:'system_msg', msg:'step_result', uid:user_uid, user_knick:user_knick, user_level:user_level, step_uid:current_step.step_uid } );
}

function reloadQuiz(e){
  if( !current_step ){
    return false;
  }
  var step_uid = current_step.step_uid;
  var data = new Object();
  data.type = 'step';
  data.mode = 'view';
  data.step_uid = step_uid;
  $('#id-quiz-view').html( '' );
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      var html = '<option value="0">퀴즈를 선택해주세요.</option>';
      if( json.quiz_list.length < 1 ){
        html = '<option value="0">설정된 퀴즈가 없습니다.</option>';
      }
      for( var i = 0 ; i < json.quiz_list.length ; i++ ){
        html += '<option value="' + json.quiz_list[i].quiz_uid + '">' + json.quiz_list[i].quest + '</option>';
      }
      $('#id_step_quiz').html( html );
    }
  });
}

function changeLoginLevel(level){
  if( level > 0 ){
    if( !confirm('리허설 모드를 시작하시겠습니까?\n리허설 모드가 시작되면 일반 이용자는 로그아웃됩니다.')){
      return false;
    }
  }else{
    if( !confirm('리허설 모드를 종료하시겠습니까?\n리허설 모드가 종료되면 일반 이용자가 로그인할 수 있습니다.')){
      return false;
    }
  }

  var data = new Object();
  data.type = 'readystatus';
  data.mode = 'update';
  data.status_code = 'live_ready';
  data.logable_level = level;
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      livestsocket.send( { cmd:'system_msg', msg:'live_ready', user_level:level } );
      alert(json.message);
      refresh();
    }
  });
}


function sendQuizResult(){
  var data = new Object();
  data.type = 'quiz_answer';
  data.mode = 'view';
  data.step_uid = current_quiz.step_uid;
  data.quiz_uid = current_quiz.quiz_uid;
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      json.cmd = 'system_msg';
      json.msg = 'quiz_result';
      json.user_level = user_level;
      livestsocket.send( json );
    }
  });
}

function sendUserRefresh(){
  if( confirm('모든 접속자 화면을 새로고침 하시겠습니까?')){
    livestsocket.send( { cmd:'system_msg', msg:'page_refresh' } );
  }
}

function sendUserLogout(){
  if( confirm('모든 접속자를 강제로 로그아웃 하시겠습니까?')){
    livestsocket.send( { cmd:'system_msg', msg:'logoutall' } );
  }
}

function kickout( msg, s_id, u_id ){
  var data = {
    cmd:'system_msg'
    ,msg:'kickout'
    ,session_id:session_id
    ,message:msg
    ,kick_session_id:s_id
    ,kick_user_id:u_id
  };
  //console.log(u_id);
  //console.log( data );
  livestsocket.send( data );
}
