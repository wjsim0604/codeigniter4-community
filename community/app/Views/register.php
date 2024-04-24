<div class="row g-0 mt-md-5 mt-2 text-center">
    <h2>회원가입</h2>
    <div class="row g-0 justify-content-center">
        <div class="col-md-6 col-12">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="register-name" placeholder="이름" autocomplete="off" aria-label="이름" aria-describedby="basic-addon1">
                <input type="text" class="form-control" id="register-mobile" placeholder="휴대폰번호" autocomplete="off" aria-label="휴대폰번호" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="register-nick" placeholder="닉네임" autocomplete="off" aria-label="닉네임" aria-describedby="basic-addon1">
                <button class="btn btn-outline-success" type="button" id="duplicate-nick" onclick="DuplicateNick()">중복검사</button>
            </div>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="register-id" placeholder="아이디" autocomplete="off" aria-label="아이디" aria-describedby="basic-addon1">
                <button class="btn btn-outline-success" type="button" id="duplicate-id" onclick="DuplicateId()">중복검사</button>
            </div>
            <div class="input-group mb-3">
                <input type="password" class="form-control" id="register-pw1" placeholder="비밀번호" aria-label="비밀번호" aria-describedby="basic-addon1">
                <input type="password" class="form-control" id="register-pw2" placeholder="비밀번호 확인" aria-label="비밀번호 확인" aria-describedby="basic-addon1">
            </div>
            <button type="button" onclick="doRegister()" class="btn btn-success">회원가입</button>
        </div>
    </div>
</div>
<script>
    function DuplicateId () {
        const val = $('#register-id').val();
        const regId = /^[A-Za-z][A-Za-z0-9_]{5,11}$/g;
        if (!regId.test(val)) {
            if (!val) {
                alert('아이디를 입력하세요.');
                return;
            } else if (val.length < 6 || val.length > 12) {
                alert('아이디는 6~12 길이로 입력해주세요.');
                return;
            } else {
                alert('아이디는 영문자로 시작하여 영문자, 숫자, 밑줄(_) 문자만 이용 가능합니다.');
                return;
            }
        }
        
        $.ajax({
            url: "/register/id",
            type: "POST",
            data: {'id': val},
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            dataType: "json",
            success: function (data) {
                if (data == 0) {
                    alert('사용 가능한 아이디 입니다.');
                    document.getElementById('duplicate-id').disabled = true;
                    document.getElementById('register-id').disabled = true;
                } else {
                    alert('중복된 아이디가 존재합니다.');
                }
            }
        });
    }

    function DuplicateNick () {
        const val = $('#register-nick').val();
        if (!val) {
            alert('닉네임을 입력하세요.');
            return;
        } else if (val.length < 2) {
            alert('닉네임은 2글자 이상 입력해주세요.');
            return;
        }
        
        $.ajax({
            url: "/register/nick",
            type: "POST",
            data: {'nick': val},
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            dataType: "json",
            success: function (data) {
                if (data == 0) {
                    alert('사용 가능한 닉네임 입니다.');
                    document.getElementById('duplicate-nick').disabled = true;
                    document.getElementById('register-nick').disabled = true;
                } else {
                    alert('중복된 닉네임이 존재합니다.');
                }
            }
        });
    }

    function doRegister () {
        const name = document.getElementById('register-name').value;
        const mobile = document.getElementById('register-mobile').value;
        const id = document.getElementById('register-id');
        const nick = document.getElementById('register-nick');
        const pw = document.getElementById('register-pw1').value;
        const pwCheck = document.getElementById('register-pw2').value;
        const regId = /^[A-Za-z][A-Za-z0-9_]{5,11}$/g;
        const regMobile = /^(02.{0}|01.{1}|0[1-9][0-9])[-]{0,1}([0-9]+)[-]{0,1}([0-9]{4})$/g;
        if (!regId.test(id.value)) {
            if (!id.value) {
                alert('ID를 입력해주세요.');
            } else if (id.value.length < 6 || id.value.length > 12) {
                alert('ID는 6~12 길이로 입력해주세요.');
            } else {
                alert('ID는 영문자로 시작하여 영문자, 숫자, 밑줄(_) 문자만 이용 가능합니다.');
            }
        } else if (!id.disabled) {
            alert('ID 중복검사를 해주세요');
        } else if (!nick.disabled) {
            alert('닉네임 중복검사를 해주세요');
        } else if (!pw || pw.length < 6) {
            alert('패스워드를 6자 이상 입력해주세요.');
        } else if (!pwCheck) {
            if (!pwCheck) {
                alert('패스워드를 재입력 해주세요.');
            }
        } else if (pw !== pwCheck) {
            alert('재입력한 패스워드가 일치하지 않습니다.');
        } else if (!nick.value) {
            alert('닉네임을 입력해주세요.');
        } else if (!name) {
            alert('이름을 입력해주세요.');
        } else if (!regMobile.test(mobile)) {
            if (!mobile) {
                alert('휴대폰 번호를 입력해주세요.');
            } else {
                alert('올바르지 않은 휴대폰 번호 형식입니다.');
            }
        } else {
            const data = {
                id: id.value,
                pw: pw,
                nick: nick.value,
                name: name,
                mobile: mobile
            }
            $.ajax({
                url: "/doRegister",
                type: "POST",
                data: data,
                dataType: "json",
                success: function (data) {
                    alert(data.message);
                    location.href = '/login';
                }
            });
        }
    }
</script>
