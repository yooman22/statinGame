</main>


<!-- 사용자 추가 팝업 -->
<div id="popup_user_regist" class="popup" title="사용자">

  <input type="hidden" name="user_uid" class="form-control" value="" />
  <input type="hidden" name="user_name" id="id_user_name" class="form-control" value="" />
  <input type="hidden" name="user_knick" id="id_user_knick" class="form-control" value="" />
  <input type="hidden" name="user_phone" id="id_user_phone" class="form-control" value="" />
  <input type="hidden" name="user_email" id="id_user_email" class="form-control" value="" />
  <input type="hidden" name="user_depart" id="id_user_depart" class="form-control" value="" />
  <input type="hidden" name="user_area" id="id_user_area" class="form-control" value="" />

<div class="form-group">
  <label for="id_user_id">로그인 아이디</label>
  <div class="input-group">
    <input type="text" id="id_user_id" name="user_id" class="form-control" />
    <div class="input-group-append">
      <input type="hidden" name="check_overlap_id" value="N" />
      <button type="button" class="btn btn-primary" onclick="checkOverlapId()">아이디중복확인</button>
    </div>
  </div>
  <div id="id_check_overlap_msg_id">아이디 중복 체크를 해주세요.</div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="id_user_name">비밀번호</label>
      <input type="text" id="id_user_pwd" name="user_pwd" class="form-control" />
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="id_user_level">등급</label>
      <select id="id_user_level" name="user_level" class="form-control">
        <option value="0" selected>이용자</option>
        <option value="9">운영자</option>
        <option value="10">최고관리자</option>
      </select>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="id_user_cscode">거래처코드</label>
      <input type="text" id="id_user_cscode" name="user_cscode" class="form-control" />
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="id_user_hospital">거래처명</label>
      <input type="text" id="id_user_hospital" name="user_hospital" class="form-control" />
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="id_user_mrname">담당MR명</label>
      <input type="text" id="id_user_mrname" name="user_mrname" class="form-control" />
    </div>
  </div>
</div>
<div class="popup-footer">
  <button type="button" class="btn btn-primary" onclick="submit_add_user()">저장</button>
  <button type="button" class="btn btn-secondary" onclick="closePopup('#popup_user_regist')">닫기</button>
</div>
</div>

<script>
$(document).ready(function(){
  $('#id_step_date').datepicker();
});
</script>

<!-- 차수 추가 팝업 -->
<div id="popup_step_regist" class="popup" title="차시 추가">
  <input type="hidden" name="step_uid" value="" />
  <div class="form-group">
    <label for="id_step_title">차시명</label>
    <input type="text" id="id_step_title" name="step_title" class="form-control" />
  </div>
  <div class="form-group">
    <label for="id_step_video">영상 URL</label>
    <input type="text" id="id_step_video" name="step_video" class="form-control" />
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="id_step_date">송출일시</label>
        <input type="text" id="id_step_date" name="step_date" class="form-control" />
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="id_step_wincount">Winner 수</label>
        <input type="text" id="id_step_wincount" name="step_wincount" class="form-control" />
      </div>
    </div>
  </div>
  <table class="table">
    <colgroup>
      <col width="80" />
      <col width="auto" />
      <col width="100" />
      <col width="50" />
    </colgroup>
    <thead>
      <tr>
        <th>순번</th>
        <th>질문</th>
        <th>대기시간</th>
        <th>정답</th>
      </tr>
    </thead>
    <tbody id="id_quiz_list"></tbody>
  </table>
  <div class="popup-footer">
    <button type="button" class="btn btn-primary" onClick="submit_add_step()">저장</button>
    <button type="button" class="btn btn-secondary" onclick="closePopup('#popup_step_regist')">닫기</button>
  </div>
</div>

<!-- 퀴즈 추가 팝업 -->
<div id="popup_quiz_regist" class="popup" title="퀴즈 추가">
  <input type="hidden" name="quiz_uid" value="" />
<div class="form-group">
  <label for="id_quest">문제</label>
  <input type="text" id="id_quest" name="quest" class="form-control" />
</div>
<div class="form-group">
  <label for="id_wait_count">대기시간(초)</label>
  <select  id="id_wait_count" name="wait_count" class="form-control">
    <option value="5">5초</option>
    <option value="3">3초</option>
  </select>
</div>
<div class="form-group">
  <table class="table">
    <colgroup>
      <col width="80" />
      <col width="auto" />
      <col width="60" />
      <col width="80" />
    </colgroup>
    <thead>
      <tr>
        <td colspan="3">
          <div class="input-group">
            <input type="text" name="inp_view" class="form-control" onkeyup="addAnswerViewEnter(event)">
            <div class="input-group-append">
              <button type="button" class="btn btn-primary" onClick="addAnswerView( event )">보기추가</button>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td>번호</td>
        <td>보기</td>
        <td>정답</td>
        <td></td>
      </tr>
    </thead>
    <tbody id="id_view_list"></tbody>
  </table>
</div>
<div class="popup-footer">
  <button type="button" class="btn btn-primary" onClick="submit_add_quiz(event)">저장</button>
  <button type="button" class="btn btn-secondary" onclick="closePopup('#popup_quiz_regist')">닫기</button>
</div>
</div>


</body>
</html>
