
<script type="text/javascript" src="/se/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript">

$(function(){
    var oEditors = [];
    nhn.husky.EZCreator.createInIFrame({
        oAppRef: oEditors,
        elPlaceHolder: "write-content",
        sSkinURI: "/se/SmartEditor2Skin.html",
        htParams: {
            bUseToolbar: true, // 툴바 사용 여부
            bUseVerticalResizer: true, // 폼 크기 조절바 사용 여부
            bUseModeChanger: true, // 모드 탭(Editor | HTML | TEXT) 사용 여부x
        },
        fCreator: "createSEditor2"
    });

    function pasteHTML(filepath) {

        var sHTML = '';

        oEditors.getById["write-content"].exec("PASTE_HTML", [sHTML]);

    }

    //전송버튼
    $("#write-save").click(function(){
        //id가 smarteditor인 textarea에 에디터에서 대입
        oEditors.getById["write-content"].exec("UPDATE_CONTENTS_FIELD", []);
        var title = $('#write-title').val();
        var content = $('#write-content').val();

        if (title == '' || title == '<br>' || title == '<p>&nbsp;</p>') {
            alert('제목을 작성해주세요.');
            return false;
        }

        if (content == '' || content == '<br>' || content == '<p>&nbsp;</p>') {
            alert('게시글을 작성해주세요.');
            return false;
        }
        $("#write-form").submit();
    });

});
</script>
<div class="row g-0">
    <div class="col-12 fw-bold fs-4 mb-2">
        <?=$name?>
    </div>
</div>
<form action="/board/<?=$id?>/write/registerWrite" name="write" id="write-form" method="POST" enctype="multipart/form-data">
    <div class="input-group mb-3">
        <span class="input-group-text">제목</span>
        <input type="text" class="form-control" id="write-title" name="write-title" placeholder="제목을 입력하세요." autocomplete="off">
    </div>
    <textarea name="write-content" id="write-content" rows="10">
    </textarea>
    <div class="w-100 d-flex justify-content-end">
        <button type="button" id="write-save" class="btn btn-primary me-2">저장</button>
        <button type="button" onclick="history.back(-1);" class="btn btn-secondary">취소</button>
    </div>
</form>