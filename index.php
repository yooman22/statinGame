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
include_once("./conf/dbconn.php");
include_once("./conf/config.php");
?>

</head>
<body>
<header>
   <div id="id-front-header" class="front-header">
     <ul>
       <li class="_left"><img src="./images/index_logo_01.png?v=1.1" /></li>
       <li class="_right"><img src="./images/index_logo_04.png?v=1.1" /></li>
       <li></li>
     </ul>
   </div>
 </header>
<style>

.index_banner_01 {
  width:61%;
  max-width:683px;
  height:383px;
  float:left;
}

.index_banner_02 {
  width:39%;
  max-width:435px;
  float:left;
}

.index_login_container {
  padding-left:30px;
}

.index_login_box ul li {
  float:left;
}

.index_login_box ul li._inp {
  width:54%;
  max-width:218px;
}

.index_login_box ul li._btn {
  width:22.5%;
  max-width:185px;
  font-size:13px;
  margin-left:0.5%;
}

._btn01 {
  height:73px;
  border:none !important;
}

._btn02 {
  height:36px;
  border:none !important;
  margin-bottom:2px;
}

._btn03 {
  height:35px;
  border:none !important;
  background-color:#aaa !important;
}

.index_login_box ul li._btn button {
  width:100%;
  background-color:#000;
  color:#FFF;
  padding:5px;
  border:1px solid #000;
}

.index_login_box ul li._btn button._login {
  border-bottom:1px solid #EAEAEA;
}

.index_login_box ul li._btn button._regist {
  border:1px solid #0C4DA2;
  border-top:1px solid #EAEAEA;
  background-color:#0C4DA2;
}

.index_login_box ul li._btn button a {
  color:#FFF;
  text-decoration: none;
}

.index_login_box ul li input {
  width:100%;
  background-color:#F7F7F7;
  border:1px solid #EAEAEA;
  padding:5px;
}

.index_login_box ul li input::placeholder {
  color:#B0ACAC;
}

.index_login_text {
  padding:0px;
  padding-left:30px;
  padding-top:2px;
  clear:both;
}

.index_login_text img { width:auto !important; height:auto; }

.index_banner_03 {
  padding:0px;
  padding-left:30px;
  padding-top:0;
  clear:both;
}

.index_banner_04 {
  padding-top:30px;
}

.index_banner_04 img {
  box-shadow:5px 5px 5px rgba(0,0,0,0.5);
}

.index_banner_05 {
  padding:20px 0;
  text-align: center;
}

.index_banner_05 button, .index_banner_05 button img {
  border:0px;
}

</style>








<div class="main-layout">
  <div class="main-container">
    <ul>
      <li class="index_banner_01">
        <!--<img src="/images/index_banner_01.png?v=1" />-->
        <iframe width="100%" height="100%" src="https://www.youtube.com/embed/TzSN7kuWoQE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      </li>
      <li class="index_banner_02">
        <div class="index_login_container">
          <div id="id-user-login" class="index_login_box">
            <?php
            if( $ready_status->logable_level > 0 ){
              echo '<ul><li><p style="color:red;font-weight:bold;">방송 준비중으로 로그인이 제한됩니다.</p></li></ul>';
            }
            ?>
            <ul>
              <li class="_inp">
                <input type="text" name="user_id" value="" placeholder="아이디(영문과 숫자조합)" tabindex="1" onkeyup="onLoginKeyUp(event)" />
                <input type="password" name="user_pwd" value="" placeholder="비밀번호(영문과 숫자조합)" tabindex="2" onkeyup="onLoginKeyUp(event)" />
              </li>
              <li class="_btn"><button type="button" class="_login _btn01" tabindex="3" onclick="login()">로그인</button></li>
              <li class="_btn _btn02">
				<button type="button" class="_regist _btn02" tabindex="4" onclick="openUserAddPopup()">회원가입</button>
				<button type="button" class="_lost _btn03" tabindex="5" onclick="openUserFindPopup()">비밀번호찾기</button>
              </li>
            </ul>
            <ul>
            </ul>
          </div>
        </div>
        <div class="index_login_text">
		  <img src="./images/index_login_text.png" />
        </div>
        <div class="index_banner_03">
          <img src="./images/index_banner_03.png" />
        </div>
      </li>
    </ul>
    <ul>
      <li class="index_banner_04">
        <a href="../upfile/brochure_001.pdf" target="_blank">
          <img src="./images/index_banner_02.png" />
        </a>
      </li>
    </ul>
    <ul>
      <li class="index_banner_05">
            <img src="./images/index_quiz_time.png" />
      </li>
    </ul>
  </div>
</div>
<div class="index-footer">
  <ul>
    <li>
      <img src="./images/footer_info.png?v=2" />
    </li>
    <li style="text-align:right;padding-right:100px;">
      <img src="./images/footer_menu_01.png" />
      <img src="./images/footer_menu_02.png" />
      <img src="./images/footer_menu_03.png" />
    </li>
  </ul>
</div>











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
