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


// 사용자 추가 팝업
function openUserAdd(){
  $('#popup_user_regist').dialog({
    width:500
    ,modal:true
    ,open:function(){
      clearPopupForm('#popup_user_regist');
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
      loadUserInfo(user_uid);
    }
  });
}

function checkOverlapId(){
  var f = $('#popup_user_regist');
  var data = new Object();
  data.type = 'user';
  data.mode = 'search_id';
  data.user_id = f.find('input[name="user_id"]').val();

  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      if( json.message ){
        $('#id_check_overlap_msg_id').html( '<p style="color:#000;">' + json.message + '</p>' );
      }else if( json.count > 0 ){
        $('#id_check_overlap_msg_id').html( '<p style="color:red;">이미 사용중인 아이디입니다. 다른 아이디를 입력해주세요.</p>' );
      }else{
        $('#id_check_overlap_msg_id').html( '<p style="color:blue;">사용 가능한 아이디입니다.</p>' );
      }
    }
  });
}

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
      }else if( json.count > 0 ){
        $('#id_check_overlap_msg_knick').html( '<p style="color:red;">이미 사용중인 별명입니다. 다른 별명을 입력해주세요.</p>' );
      }else{
        $('#id_check_overlap_msg_knick').html( '<p style="color:blue;">사용 가능한 별명입니다.</p>' );
      }
    }
  });
}

function submit_add_user(){
  var f = $('#popup_user_regist');
  var data = new Object();
  data.type = 'user';
  var user_uid = f.find('input[name="user_uid"]').val().trim();
  if( user_uid ){
    data.mode = 'update';
    data.user_uid = user_uid;
  }else{
    data.mode = 'insert';
  }

  data.user_id = f.find('input[name="user_id"]').val().trim();
  /*data.user_name = f.find('input[name="user_name"]').val().trim();
  data.user_phone = f.find('input[name="user_phone"]').val().trim();
  data.user_depart = f.find('input[name="user_depart"]').val().trim();
  data.user_email = f.find('input[name="user_email"]').val().trim();
  data.user_knick = f.find('input[name="user_knick"]').val().trim();
  data.user_area = f.find('select[name="user_area"]').val();*/
  data.user_pwd = f.find('input[name="user_pwd"]').val().trim();
  data.user_level = f.find('select[name="user_level"]').val();
  data.user_cscode = f.find('input[name="user_cscode"]').val().trim();
  data.user_hospital = f.find('input[name="user_hospital"]').val().trim();
  data.user_mrname = f.find('input[name="user_mrname"]').val().trim();
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      alert(json.message);
      refresh();
    }
  });
}

// 차시 수정
function openStepAdd(){
  $('#popup_step_regist').dialog({
    width:800
    ,modal:true
    ,open:function(){
      clearPopupForm('#popup_step_regist');
    }
  });
}

// 차시 수정
function openStepEdit( step_uid ){
  $('#popup_step_regist').dialog({
    width:800
    ,modal:true
    ,open:function(e){
      $('#popup_step_regist').find('input[name="step_uid"]').val( step_uid );
      var data = new Object();
      data.type = 'step';
      data.mode = 'view';
      data.step_uid = step_uid;
      jsonPost({
        url:'../../admin/proc/adminApi.php'
        ,data:data
        ,success:function(json){
          $('#popup_step_regist').find('input[name="step_title"]').val( json.step_title );
          $('#popup_step_regist').find('input[name="step_video"]').val( json.step_video );
          $('#popup_step_regist').find('input[name="step_date"]').val( json.step_date );
          $('#popup_step_regist').find('input[name="step_wincount"]').val( json.step_wincount );
          var html = '';
          for( var i = 0 ; i < json.quiz_list.length ; i++ ){
            html += '<tr class="quiz_list_item" data-uid="' + json.quiz_list[i].quiz_uid +'">';
            html += '<td class="show_num"><input type="text" name="show_num" value="' + json.quiz_list[i].show_num + '" class="form-control"/></td>';
            html += '<td><a href="javascript:;" onclick="showQuiz(' + json.quiz_list[i].quiz_uid + ');">' + json.quiz_list[i].quest + '</a></td>';
            html += '<td>' + json.quiz_list[i].wait_count + '</td>';
            html += '<td><span title="' + json.quiz_list[i].correct_view_content + '" style="cursor:default;">' + json.quiz_list[i].correct_view_seq + '</span></td>';
            //html += '<td><a href="javascript:;" onclick="quizUp(event)">UP</a>&nbsp;&nbsp;<a href="javascript:;" onclick="quizDown(event)">DOWN</a></td>';
            html += '</tr>';
          }
          $('#id_quiz_list').html( html );
          //updateQuizList();
        }
      });
    }
  });
}

function quizUp(e){
  var current_index = $(e.target).parent().parent().index();
  console.log( current_index );
  if( current_index > 0 ){
    $(e.target).parent().parent().insertBefore($(e.target).parent().parent().parent().find('tr').eq( current_index - 1 ));
  }
  updateQuizList();
}

function quizDown(e){
  var current_index = $(e.target).parent().parent().index();
  $(e.target).parent().parent().insertAfter($(e.target).parent().parent().parent().find('tr').eq( current_index + 1));
  updateQuizList();
}


function submit_add_step(){
  var f = $('#popup_step_regist');
  var data = new Object();
  data.type = 'step';
  data.mode = 'insert';
  if(f.find('input[name="step_uid"]').val()){
    data.step_uid = parseInt( f.find('input[name="step_uid"]').val() );
    data.mode = 'modify';
  }
  data.step_title = f.find('input[name="step_title"]').val();
  data.step_video = f.find('input[name="step_video"]').val();
  data.step_date = f.find('input[name="step_date"]').val();
  data.step_wincount = parseInt( f.find('input[name="step_wincount"]').val() );
  data.quiz_list = [];
  var i = 0;
  f.find('.quiz_list_item').each(function(idx,ele){
    i++;
    data.quiz_list.push(
      {
        show_num:parseInt( $(this).find('input[name="show_num"]').val().trim() )
        ,quiz_uid:parseInt( $(this).data('uid') )
      }
    );
  });

  jsonPost({
    url:'../../admin/proc/adminApi.php'
    ,data:data
    ,success:function(json){
      alert(json.message);
      //refresh();
    }
  });
}

// 퀴즈 추가 팝업
function openQuizAdd(){
  $('#popup_quiz_regist').dialog({
    width:800
    ,modal:true
    ,open:function(){
      clearPopupForm('#popup_quiz_regist');
      $('#id_view_list').html('');
    }
  });
}

function addAnswerViewEnter( e ){
  if( e ){
    if( e.keyCode == 13 ){
      e.preventDefault();
      addAnswerView();
      return;
    }else{
      return;
    }
  }
}

function addAnswerView( e ){
  var f = $('#popup_quiz_regist');
  var str = f.find('input[name="inp_view"]').val();
  if( str ){
    str = str.trim();
  }else{
    return false;
  }
  $('#id_view_list').append(
    '<tr class="view_list_item"><td class="view_list_num"></td><td class="view_list_quest">' + str + '</td><td><input type="radio" name="inp_correct" /></td><td><button type="button" class="btn btn-primary" data-uid="" onclick="deleteQuizView(event)">삭제</button></td></tr>'
  );

  updateViewList();

  f.find('input[name="inp_view"]').val('');
}

function updateViewList(){
  $('#id_view_list .view_list_num').each(function( idx, ele ){
    $(this).text( idx + 1);
  });

  $('#id_view_list').find('input:radio[name="inp_correct"]').each(function( idx, ele ){
    $(this).attr('value', idx + 1);
  });
}

function updateQuizList(){
  $('#id_quiz_list .show_num').each(function( idx, ele ){
    $(this).text( idx + 1);
  });
}

function submit_add_quiz( e ){
  var f = $('#popup_quiz_regist');
  var data = new Object();
  data.type='quiz';
  if( f.find('input[name="quiz_uid"]').val() ){
    data.mode = 'modify';
    data.quiz_uid = parseInt(f.find('input[name="quiz_uid"]').val());
  }else{
    data.mode = 'insert';
  }
  data.currect = 0;
  data.wait_count = 0;
  data.wait_count = parseInt( f.find('select[name="wait_count"]').val() );
  data.quest = f.find('input[name="quest"]').val();
  f.find('input:radio[name="inp_correct"]:checked').each( function( idx, ele ){
    data.currect = parseInt( $(this).val() );
  });
  data.view_list = [];
  f.find('.view_list_item').each(function(idx,ele){
    data.view_list.push(
      {
        "view_content":$(this).find('.view_list_quest').text()
        ,"seq_no":$(this).find('input:radio[name="inp_correct"]').attr('value')
      }
    );
  });

  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      alert(json.message);
      if($('.ui-dialog-titlebar').length > 1 ){
        f.dialog('close');
      }else{
        //refresh();
      }
    }
  });
}


function showQuiz( uid ){
  $('#popup_quiz_regist').dialog({
    width:800
    ,modal:true
    ,open:function(e){

      var data = new Object();
      data.quiz_uid = uid;
      data.type = 'quiz';
      data.mode = 'view';

      jsonPost({
        url:'/admin/proc/api.php'
        ,data:data
        ,success:function(json){
          var f = $('#popup_quiz_regist');
          f.find('input[name="quiz_uid"]').val( json.quiz_uid );
          f.find('input[name="quest"]').val( json.quest );
          f.find('input[name="wait_count"]').val( json.wait_count );
          var html = '';
          if( json.view_list ){
            for( var i = 0 ; i < json.view_list.length ; i++ ){
              html += '<tr class="view_list_item" data-uid="' + json.view_list[i].view_uid + '">';
              html += '<td class="view_list_num">' + json.view_list[i].seq_no + '</td>';
              html += '<td class="view_list_quest">' + json.view_list[i].view_content + '</td>';
              html += '<td><input type="radio" name="inp_correct" ' + ((json.view_list[i].view_uid == json.view_list[i].correct_uid )?'checked':'') + ' /></td>';
              html += '<td><button type="button" class="btn btn-primary" data-uid="' + json.view_list[i].view_uid + '" onclick="deleteQuizView(event)">삭제</button></td>';
              html += '</tr>';
            }
            $('#id_view_list').html( html );
            updateViewList();
          }
        }
      });
    }
  });
}

function deleteQuizView( e ){
  var uid = $( e.target ).data( 'uid' );
  var data = new Object();
  data.type = 'quiz_view';
  data.mode = 'delete';
  data.view_uid = uid;
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      $( e.target ).parent().parent().remove();
    }
  });
}

function saveStepQuiz( step_uid ){
  var data = new Object();
  data.type = 'step';
  data.mode = 'add_quiz';
  data.step_uid = step_uid;
  data.quiz_list = [];
  $('input:checkbox[name="quiz_check"]:checked').each(function(idx,ele){
    data.quiz_list.push({quiz_uid:parseInt($(this).val())});
  });
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      alert(json.message);
      refresh();
    }
  });
}

function deleteSelectedQuiz(){
  if( !confirm('퀴즈를 삭제하면 해당 퀴즈가 사용된 결과에 문제가 발생됩니다.\n그래도 삭제하시겠습니까?')){
    return false;
  }
  var data = new Object();
  data.type = 'quiz';
  data.mode = 'delete';
  data.quiz_list = [];
  $('input:checkbox[name="quiz_check"]:checked').each(function(idx,ele){
    data.quiz_list.push({quiz_uid:parseInt($(this).val())});
  });
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      alert(json.message);
      refresh();
    }
  });
}

function checkAllQuiz( e ) {
  $('input[name="quiz_check"]').each(function(idx,ele){
    $(this).prop('checked',$(e.target).is(':checked'));
  });
}

//차시 삭제
function deleteSelectedStep(){
  if( !confirm('차시를 삭제하면 해당 차시결과에 문제가 발생됩니다.\n그래도 삭제하시겠습니까?')){
    return false;
  }
  var data = new Object();
  data.type = 'step';
  data.mode = 'delete';
  data.step_list = [];
  $('input:checkbox[name="step_check"]:checked').each(function(idx,ele){
    data.step_list.push({step_uid:parseInt($(this).val())});
  });
  jsonPost({
    url:'../../admin/proc/adminApi.php'
    ,data:data
    ,success:function(json){
      alert(json.message);
      refresh();
    }
  });
}

function checkAllStep( e ) {
  $('input[name="step_check"]').each(function(idx,ele){
    $(this).prop('checked',$(e.target).is(':checked'));
  });
}


function loadUserInfo( user_uid ){
  var data = new Object();
  data.type = 'user';
  data.mode = 'view';
  data.user_uid = user_uid;
  jsonPost({
    url:'/admin/proc/api.php'
    ,data:data
    ,success:function(json){
      console.log( json );
      var f = $('#popup_user_regist');
      f.find('input[name="user_uid"]').val( json.user_uid );
      f.find('input[name="user_name"]').val( json.user_name );
      f.find('input[name="user_id"]').val( json.user_id );
      f.find('input[name="user_knick"]').val( json.user_knick );
      f.find('input[name="user_phone"]').val( json.user_phone );
      f.find('input[name="user_email"]').val( json.user_email );
      f.find('input[name="user_hospital"]').val( json.user_hospital );
      f.find('input[name="user_depart"]').val( json.user_depart );
      f.find('select[name="user_level"]').val( json.user_level );
      f.find('input[name="user_cscode"]').val( json.user_cscode );
      f.find('input[name="user_mrname"]').val( json.user_mrname );
    }
  })
}

function deleteUser( user_uid, user_id ){
  console.log( user_uid );
  if( confirm( '"' + user_id + '" 사용자를 삭제하시겠습니까?') ){
    var data = new Object();
    data.type = 'user';
    data.mode = 'delete';
    data.user_uid = user_uid;
    jsonPost({
      data:data
      ,url:'/admin/proc/api.php'
      ,success:function(json){
        alert(json.message);
        refresh();
      }
    });
  }
}

function copyStep( step_uid ){
  if(confirm('차시를 복사하시겠습니까?')){
    jsonPost({
      data:{type:'step',mode:'copy',step_uid:step_uid}
      ,url:'/admin/proc/api.php'
      ,success:function(json){
        alert(json.message);
        refresh();
      }
    });
  }
}

function truncateStep( step_uid ){
  if(confirm('차시 데이터를 초기화하시겠습니까?\n해당 차시의 응답 데이터가 모두 삭제됩니다.')){
    jsonPost({
      data:{type:'step',mode:'truncate',step_uid:step_uid}
      ,url:'/admin/proc/api.php'
      ,success:function(json){
        alert(json.message);
        refresh();
      }
    });
  }
}


function jsonPost( param ){
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

function clearPopupForm( target ){
  $( target ).find('input').each(function(index,ele){
    $(this).val('');
  });
  $( target ).find('select').each(function(index,ele){
    $(this).val('');
  });
}

/**
 * 숫자에 컴마 표시
 * @param nStr
 * @returns
 */
function addCommas(nStr)
{
	nStr += '';
	nStr = nStr.replace( ',','');
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

/**
 * 숫자값에 컴마 제거
 * @param str
 * @returns
 */
function removeCommas( str ) {
	return str.split(',').join('');
}
