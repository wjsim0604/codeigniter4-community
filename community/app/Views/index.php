<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="/css/style.css" rel="stylesheet"/>
</head>
<body>
    <script src="https://cdn.socket.io/4.3.1/socket.io.min.js"></script>
    <script>
        var socket = io('http://localhost:3003', { transports: [ 'websocket' ], reconnection: true, reconnectionAttempts: 5, debug: false });
        socket.on('message', function(data) {
            showMessage(data);
        });
        function displayChat () {
            var chat = $('#chat-manager');
            if (chat.hasClass('d-block')) {
                chat.addClass('d-none');
                chat.removeClass('d-block');
            } else {
                chat.addClass('d-block');
                chat.removeClass('d-none');
            }
        }
        function sendMessage(user) {
            if (!user) {
                alert('로그인 후 이용해주세요.');
                return;
            }
            const messageInput = document.getElementById('chat-message');
            if (!messageInput.value) {
                alert('메세지를 입력하세요.');
                return;
            }
            const data = {
                command: 'message',
                message: messageInput.value,
                userInfo: user
            }
            socket.emit('message', data);
            messageInput.value = '';
        }
        function showMessage(message) {
            var chatView = $('#chat-view-area');
            var chat = document.querySelector('.chat-content-wrapper');
            var res = '';
            res = '<div class="my-1">';
            // res += '<img src="/img/level/' + message.userInfo.MB_LEVEL + '.png" width="30px" height="30px" />';
            res += '<span class="me-2"><strong>' + message.userInfo.mb_nick + '</strong></span>';
            res += '<span>'+ message.message +'</span>';
            res += '</div>';
            chatView.append(res);
            chat.scrollTop = chat.scrollHeight;
        }
        function enterMessage (user) {
            var keycode = window.event.keyCode;
            if (keycode === 13) {
                if (!user) {
                    alert('로그인 후 이용해주세요.');
                    return;
                }
                sendMessage(user);
            }
        }
    </script>
    <header class="bg-success text-white header-top-01">
        <div class="container-lg">
            <div class="row g-0 justify-content-between align-items-center">
                <div class="col-md-4 col-6 p-2">
                    <a href="/" class="fs-2 home"><?= $title ?></a>
                </div>
                <div class="col-md-4 col-6 text-end">
                    <?php
                        helper('session');
                        $member = session()->get('user');
                        if ($member) {
                    ?>
                        <a class="mx-4" href="/myinfo"><?=$member->mb_nick?> [ <?=$member->mb_id?> ]</a>
                        <a class="mx-4" href="/logout">로그아웃</a>
                    <?php
                        } else {
                    ?>
                        <a class="mx-4" href="/register">회원가입</a>
                        <a class="mx-4" href="/login">로그인</a>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </header>
    <header class="bg-success-subtle text-white header-top-02">
        <div class="container-lg">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand category-title">카테고리</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <?php foreach ($categories as $category) { ?>
                                <li class="nav-item me-md-5">
                                    <a class="nav-link" href="/board/<?= $category['ca_url'] ?>/1"><?= $category['ca_name'] ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <div class="container my-2 main-layout">
        <div class="row g-0">
            <button class="btn btn-sm btn-success mb-2" id="chat-open" onclick="displayChat()">채팅</button>
            <div class="col-md-3 col-12 me-md-2 mb-md-0 mb-2" id="chat-manager">
                <div class="chat-area border">
                    <div class="chat-content-wrapper border-bottom p-3" id="chat-view-area">
                    </div>
                    <div class="chat-input-wrapper">
                        <div class="d-flex h-100">
                            <?php
                                if ($member) {
                            ?>
                                <input type="text" class="chat-input p-2" id="chat-message" placeholder="채팅을 입력해주세요." autocomplete="off" onkeydown='enterMessage(<?php echo json_encode($member) ?>)' />
                                <button class="btn btn-sm btn-success chat-enter" onclick='sendMessage(<?php echo json_encode($member) ?>)'><i class="chat-enter-icons"></i></button>
                            <?php
                                } else {
                            ?>
                                <input type="text" class="chat-input p-2" id="chat-message" disabled placeholder="로그인 후 이용해주세요." autocomplete="off" />
                                <button class="btn btn-sm btn-success chat-enter" disabled><i class="chat-enter-icons"></i></button>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md col-12">
                <?= $content ?>
            </div>
        </div>
    </div>
    <?php
        include (APPPATH. 'Views/footer.php');
    ?>
</body>
</html>