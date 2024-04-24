<?php
    helper('session');
    $member = session()->get('user');
?>
<div class="row g-0 mb-2">
    <div class="col-md col-12">
        <h3><?= $name ?></h3>
    </div>
    <div class="col-md-4 col-12">
        <form class="d-flex" action="/board/<?=$posts['id']?>/1" method="get">
            <select class="form-select w-25 me-2" name="kind" id="kind" required>
                <option selected value="1">제목</option>
                <option value="2">내용</option>
            </select>
            <input class="form-control me-2" type="search" name="search" id="search" placeholder="검색" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">search</button>
        </form>
    </div>
    <?php
        if ($isWrite == 0 || $member->mb_permission == '900') {
    ?>
        <div class="col-md-2">
            <div class="d-flex justify-content-end">
                <a href="/board/<?=$posts['id']?>/write" class="btn btn-success">글쓰기</a>
            </div>
        </div> 
    <?php
        }
    ?>
</div>
<ul class="list-group mb-2">
    <div class="list-group-item list-group-item-light">
        <div class="row text-center">
            <div class="col-md-6 col-5 text-start">제목</div>
            <div class="col-2">작성자</div>
            <div class="col-2">날짜</div>
            <div class="col-md-1 col-2">조회</div>
            <div class="col-1" style="line-height: 12px;"><img src="/img/icons/hand-thumbs-up-fill.svg" alt="" srcset=""></div>
        </div>
    </div>
    <?php
        if (count($posts['data']) > 0) {
            foreach ($posts['data'] as $post) { 
    ?>
        <a href="/read/<?=$post['ca_url']?>/<?=$posts['page']?>/<?=$post['bc_seq']?>" class="list-group-item list-group-item-action list-group-item-light">
            <div class="row text-center">
                <div class="col-6 text-start text-truncate">
                    <span class=""><?= $post['bc_title'] ?></span>
                    <span class="text-success fw-bold ms-2 board-mini"><?= $post['cmt'] ?></span>
                </div>
                <div class="col-2 text-truncate"><?= $post['mb_nick'] ?></div>
                <div class="col-2 text-secondary board-mini"><?= $post['created'] ?></div>
                <div class="col-1 text-secondary board-mini"><?= $post['bc_read'] ?></div>
                <div class="col-1 text-secondary fw-bold board-mini"><?= $post['up'] ?></div>
            </div>
        </a>
    <?php
            }
        } else {
    ?>
        <div class="list-group-item list-group-item-action list-group-item-light text-center">게시글이 존재하지 않습니다.</div>
    <?php
        }
    ?>
</ul>
<?php
    if ($posts['totalPage'] > 0) {
?>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <?php
                if ($posts['page'] == 1) {
            ?>
                <li class="page-item disabled">
            <?php
                } else {
            ?>
                <li class="page-item">
            <?php
                }
            ?>
            <?php
                if (isset($posts['kind']) && isset($posts['search'])) {
            ?>
                <a class="page-link" href="/board/<?=$posts['id']?>/<?=$posts['page'] - 1 ?>?kind=<?= $posts['kind'] ?>&search=<?= $posts['search'] ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            <?php
                } else {
            ?>
                <a class="page-link" href="/board/<?=$posts['id']?>/<?=$posts['page'] - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            <?php
                }
            ?>
            <?php
                for ($i = 0; $i < $posts['totalPage']; $i++) {
                    if ($posts['page'] == $i+1) {
            ?>
                    <li class="page-item active">
                <?php
                    } else {
                ?>
                    <li class="page-item">
                <?php
                    }
                ?>
                <?php
                    if (isset($posts['kind']) && isset($posts['search'])) {
                ?>
                    <a class="page-link" href="/board/<?=$posts['id']?>/<?=$i+1?>?kind=<?= $posts['kind'] ?>&search=<?= $posts['search'] ?>">
                        <?=$i+1?>
                    </a>
                <?php
                    } else {
                ?>
                    <a class="page-link" href="/board/<?=$posts['id']?>/<?=$i+1?>">
                        <?=$i+1?>
                    </a>
                <?php
                    }
                ?>
                </li>
            <?php
                }
            ?>
            <?php
                if ($posts['page'] == $posts['totalPage']) {
            ?>
                <li class="page-item disabled">
            <?php
                } else {
            ?>
                <li class="page-item">
            <?php
                }
            ?>
            <?php
                if (isset($posts['kind']) && isset($posts['search'])) {
            ?>
                <a class="page-link" href="/board/<?=$posts['id']?>/<?=$posts['page'] + 1 ?>?kind=<?= $posts['kind'] ?>&search=<?= $posts['search'] ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            <?php
                } else {
            ?>
                <a class="page-link" href="/board/<?=$posts['id']?>/<?=$posts['page'] + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            <?php
                }
            ?>
            </li>
        </ul>
    </nav>
<?php
    }
?>