<?php
session_start();
if(isset( $_SESSION['user_uid'] )){
  header("Location:pages/dashboard.php");
}
?>
<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Statintube</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
  <link rel="stylesheet" href="/lib/jquery-ui-1.12.1/jquery-ui.min.css" />
  <link rel="stylesheet" href="/admin/css/style.css?v=<?php echo time(); ?>" />
  <link rel="stylesheet" href="/css/style.css?v=<?php echo time(); ?>" />
  <script src="/lib/jquery-3.4.1.min.js"></script>
  <script src="/lib/jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script src="/admin/js/script.js?v=<?php echo time(); ?>"></script>
  <script>
  function onLoginKeyUp( e ){
    if( e.keyCode == 13 ){
      login();
      return false;
    }
  }

  function login(){
    //location.href = '/front/pages/live.php';
    var data = new Object();
    data.user_id = $('#id-user-login').find('input[name="user_id"]').val().trim();
    data.user_pwd = $('#id-user-login').find('input[name="user_pwd"]').val().trim();

    jsonPost({
      url:'/admin/proc/login.php'
      ,data:data
      ,success:function(json){

        if( json.message == 'success' ){
          refresh('./pages/dashboard.php');
        }else{
          alert( json.message );
        }
      }
    });
  }
  </script>
</head>
<body>
  <div class="login-container">
    <div id="id-user-login" class="login-form">
      <ul>
        <li style="height:107px;vertical-align:middle;">
          <img src="/images/txt_login.png" style="margin-bottom:25px;">
        </li>
      </ul>
            <ul>
        <li style="height:120px;">
          <p><input type="text" name="user_id" class="input-login" value="" placeholder="아이디를 입력해 주세요." onkeyup="onLoginKeyUp(event)"></p>
          <p><input type="password" name="user_pwd" class="input-login" value="" placeholder="비밀번호를 입력해 주세요." onkeyup="onLoginKeyUp(event)"></p>
        </li>
        <li class="btn-login" onclick="login()">관리자로그인</li>
      </ul>
    </div>

  </div>
</body>
</html>
