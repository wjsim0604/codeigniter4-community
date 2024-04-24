<div class="row g-0">
    <div class="col-md col-12 me-2 board-ranking-wrapper mb-md-0 mb-2 bg-light">
        <div class="row g-0">
            <div class="col-6 bg-success text-white ranking-tab" onclick="changeTab(this.id, 1)" id="ranking-tab001">
                <div class="p-2 text-center">1 ~ 10위</div>
            </div>
            <div class="col-6 ranking-tab" onclick="changeTab(this.id, 2)" id="ranking-tab002">
                <div class="p-2 text-center">11위 ~ 20위</div>
            </div>
        </div>
        <div class="row g-0">
            <?php
                if ($rankingCount == 0) {
            ?>
                <div class="col-12 p-2">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="text-secondary">등록된 게시글이 없습니다.</div>
                    </div>
                </div>
            <?php
                } else {
            ?>
                <div class="col-12 p-2" id="ranking-001">
                    <?php
                        foreach ($ranking as $key => $rank) {
                            if ($key < 10) {
                    ?>
                        <a class="d-flex align-items-center ranking-item py-2" href="/read/<?=$rank['ca_url']?>/1/<?=$rank['bc_seq']?>">
                            <?php
                                if ($key > 0) {
                            ?>
                                <span class="badge text-bg-secondary me-2"><?= $key + 1 ?></span>
                            <?php
                                } else {
                            ?>
                                <span class="badge text-bg-success me-2"><?= $key + 1 ?></span>
                            <?php
                                }
                            ?>
                            <span class="text-black-50 me-2"><?= $rank['ca_name'] ?></span>
                            <span class="me-3 text-truncate" style="max-width: 350px;"><?= $rank['bc_title'] ?></span>
                            <span class="text-success fw-bold" style="font-size: 10px;"><?= $rank['bc_read'] ?></span>
                        </a>
                    <?php
                            }
                        }
                    ?>
                </div>
                <div class="col-12 p-2 d-none" id="ranking-002">
                    <?php
                        foreach ($ranking as $key => $rank) {
                            if ($key >= 10) {
                    ?>
                        <a class="d-flex align-items-center ranking-item py-2" href="/read/<?=$rank['ca_url']?>/1/<?=$rank['bc_seq']?>">
                            <span class="badge text-bg-success me-2"><?= $key + 1 ?></span>
                            <span class="text-black-50 me-2"><?= $rank['ca_name'] ?></span>
                            <span class="me-3 text-truncate" style="max-width: 350px;"><?= $rank['bc_title'] ?></span>
                            <span class="text-success fw-bold" style="font-size: 10px;"><?= $rank['bc_read'] ?></span>
                        </a>
                    <?php
                            }
                        }
                    ?>
                </div>
            <?php
                }
            ?>
        </div>
    </div>
    <div class="col-md-6 col-12 summary-notice-wrapper bg-light">
        <div class="row g-0">
            <div class="col-12 bg-success text-white">
                <div class="p-2"><a href="/notice">공지사항</a></div>
            </div>
            <div class="col-12">
                <?php
                    if ($noticeCount == 0) {
                ?>
                    <div class="p-2 text-center text-secondary">등록된 공지사항이 없습니다.</div>
                <?php
                    } else {
                        foreach ($notices as $key => $notice) {
                ?>
                    <a class="row g-0 p-2 notice-item" href="/read/<?=$notice['ca_url']?>/1/<?=$notice['bc_seq']?>">
                        <div class="col-8 text-truncate"><?= $notice['bc_title'] ?></div>
                        <div class="col-4 text-success fw-bold" style="font-size: 10px;"><?= $notice['bc_read'] ?></div>
                    </a>
                <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<script>
    function changeTab (e, tab) {
        var wrapper = $('#ranking-00' + tab);
        var tId = $('#' + e);
        $('#ranking-tab001').removeClass('bg-success');
        $('#ranking-tab001').removeClass('text-white');
        $('#ranking-tab002').removeClass('bg-success');
        $('#ranking-tab002').removeClass('text-white');
        tId.addClass('bg-success');
        tId.addClass('text-white');
        if (tab > 1) {
            // 11 ~ 20위 클릭할 때
            // 1 ~ 10위 d-none 하기
            // 11 ~ 20위 d-block 하기
            var otherWrapper = $('#ranking-00' + (tab - 1));
            wrapper.addClass('d-block');
            wrapper.removeClass('d-none');
            otherWrapper.addClass('d-none');
            otherWrapper.removeClass('d-block');
        } else {
            // 1 ~ 10위 클릭할 때
            // 11 ~ 20위 d-none 하기
            // 1 ~ 10위 d-block 하기
            var otherWrapper = $('#ranking-00' + (tab + 1));
            wrapper.addClass('d-block');
            wrapper.removeClass('d-none');
            otherWrapper.addClass('d-none');
            otherWrapper.removeClass('d-block');
        }
    }
</script>