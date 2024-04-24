<div class="row g-0 ">
    <div class="col-6 fw-bold fs-4 mb-2">
        <?=$name?>
    </div>
    <div class="col-12 border-bottom border-top">
        <div class="d-flex flex-column">
            <div class="fs-5 py-2 text-truncate"><?= $read['content']->bc_title ?></div>
            <div class="d-flex text-secondary pb-2">
                <div class="me-3"><?= $read['content']->mb_nick ?></div>
                <div class="me-3"><?= $read['content']->created ?></div>
                <div class="me-3">조회 <?= number_format($read['content']->bc_read) ?></div>
                <div class="d-flex align-items-center">
                    <img src="/img/icons/hand-thumbs-up.svg" alt="" srcset="">
                    <div class="ms-2"><?= $read['content']->up ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 p-2 border-bottom">
        <div class="row g-0">
            <div class="col-12" id="board-content-wrapper">
                <?= $read['content']->bc_content ?>
            </div>
            <div class="col-12">
                <div class="row g-0">
                    <div class="col-md-6 col-12 mb-md-0 mb-2">
                        <form action="/addUp/<?= $id ?>/<?= $seq ?>/<?= $page ?>" method="post" class="d-flex justify-content-end">
                            <img src="/img/icons/hand-thumbs-up-fill.svg" alt="" srcset="">
                            <button type="submit" class="btn btn-outline-success ms-4">추천</button>
                        </form>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-outline-secondary me-2">▲</button>
                            <button class="btn btn-success me-2">목록</button>
                            <?php
                                if ($member && ($member->mb_seq == $read['content']->mb_seq)) {
                            ?>
                                <a href="/modify/<?= $id ?>/<?= $page ?>/<?= $seq ?>" class="btn btn-outline-primary me-2">수정</a>
                                <a href="/deleteBoard/<?= $id ?>/<?= $seq ?>" class="btn btn-outline-danger">삭제</a>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 p-2">
        <div class="row g-0">
            <?php
                if (count($read['comment']) > 0) {
                    foreach ($read['comment'] as $key => $comment) {
            ?>
                <div class="col-12 border-bottom mb-2">
                    <div class="d-flex flex-column">
                        <div class="fw-bold"><?= $comment['mb_nick'] ?></div>
                        <div class="py-3"><?= $comment['cm_comment'] ?></div>
                        <div class="text-secondary pb-2" style="font-size: 10px;"><?= $comment['created'] ?></div>
                    </div>
                </div>
            <?php
                    }
                } else {
            ?>
                <div class="col-12">
                    등록된 댓글이 없습니다.
                </div>
            <?php
                }
            ?>
        </div>
    </div>
    <?php
        helper('session');
        $member = session()->get('user');
        if ($member) {
    ?>
        <div class="col-12 p-2">
            <div class="row g-0">
                <div class="col-12">
                    <form action="/registerComment/<?= $id ?>/<?= $seq ?>/<?= $page ?>" method="post">
                        <div class="row g-0 align-items-center">
                            <div class="col-12 text-start text-secondary mb-2">댓글작성</div>
                            <textarea class="col-10 p-2 me-2" name="comment-content" rows="5"></textarea>
                            <div class="col">
                                <button type="submit" class="btn btn-secondary w-75" style="height: 50px;">등록</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
        } else {
    ?>
        <div class="col-12 p-2 text-center">
            <a href="/login" class="text-success">로그인</a> 후 댓글을 남겨주세요.
        </div>
    <?php
        }
    ?>
</div>