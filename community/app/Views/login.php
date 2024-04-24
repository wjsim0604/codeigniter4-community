<div class="row g-0 mt-md-5 mt-2 text-center">
    <h2>로그인</h2>
    <div class="row g-0 justify-content-center">
        <div class="col-md-4 col-12">
            <form action="/doLogin" method="post">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="login-id" placeholder="아이디" autocomplete="off" aria-label="아이디" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="login-pw" placeholder="비밀번호" aria-label="비밀번호" aria-describedby="basic-addon1">
                </div>
                <button type="submit" class="btn btn-success">로그인</button>
            </form>
            <p class="mt-3">아직 계정이 없으신가요? <a href="/register" class="text-primary">회원가입</a></p>

        </div>
    </div>
</div>
