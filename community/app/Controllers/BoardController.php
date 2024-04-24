<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MainModel;
use App\Models\BoardModel;

class BoardController extends BaseController
{
    function board($url, $page)
    {
        $main = new MainModel();
        $board = new BoardModel();
        $kind = $this->request->getGet('kind');
        $search = $this->request->getGet('search');
        $data['categories'] = $main->getCategory();
        $sub['name'] = $board->getBoardName($url);
        $sub['isWrite'] = $board->getBoardWrite($url);
        $sub['posts'] = $board->getPost($url, $page, $kind, $search);
        $data['content'] = view('/board/index', $sub);
        $data['title'] = '이름없음';
        return view('index', $data);
    }

    function read ($id, $page, $seq) {
        $main = new MainModel();
        $board = new BoardModel();
        helper('session');
        $sub['member'] = session()->get('user');
        $sub['read'] = $board->read($id, $page, $seq);
        $sub['seq'] = $seq;
        $sub['page'] = $page;
        $sub['id'] = $id;
        $sub['name'] = $board->getBoardName($id);
        $data['categories'] = $main->getCategory();
        $data['content'] = view('/board/read', $sub);
        $data['title'] = '이름없음';
        return view('index', $data);
    }

    function write ($id) {
        $main = new MainModel();
        $board = new BoardModel();
        $sub['id'] = $id;
        $sub['name'] = $board->getBoardName($id);
        $data['categories'] = $main->getCategory();
        $data['content'] = view('/board/write', $sub);
        $data['title'] = '이름없음';
        return view('index', $data);
    }

    function modify ($id, $page, $seq) {
        $main = new MainModel();
        $board = new BoardModel();
        $data = [
            'id' => $id,
            'seq' => $seq,
            'page' => $page
        ];
        $sub['id'] = $id;
        $sub['seq'] = $seq;
        $sub['page'] = $page;
        $sub['name'] = $board->getBoardName($id);
        $sub['modify'] = $board->modify($data);
        $data['categories'] = $main->getCategory();
        $data['content'] = view('/board/modify', $sub);
        $data['title'] = '이름없음';
        return view('index', $data);
    }

    function deleteBoard ($id, $seq) {
        $board = new BoardModel();
        $success = $board->deleteBoard($seq);
        if ($success) {
            $this->response->redirect('/board/'.$id.'/1');
        } else {
            echo "<script>alert('게시글 삭제를 실패하였습니다.');history.back();</script>";
            return;
        }
    }

    function registerWrite($id) {
        $board = new BoardModel();
        $data = [
            'id' => $id,
            'title' => $this->request->getPost('write-title'),
            'content' => $this->request->getPost('write-content')
        ];
        $success = $board->write($data);
        if ($success) {
            $this->response->redirect('/board/'.$id.'/1');
        } else {
            echo "<script>alert('게시글 등록에 실패하였습니다.');history.back();</script>";
            return;
        }
    }

    function registerModify ($id, $page, $seq) {
        $board = new BoardModel();
        $title = $this->request->getPost('write-title');
        $content = $this->request->getPost('write-content');
        $success = $board->registerModify($seq, $title, $content);
        if ($success) {
            $this->response->redirect('/read/'.$id.'/'.$page.'/'.$seq);
        } else {
            echo "<script>alert('게시글 수정에 실패하였습니다.');history.back();</script>";
            return;
        }
    }

    function registerComment ($id, $seq, $page) {
        helper('session');
        $board = new BoardModel();
        $member = session()->get('user');
        $comment = $this->request->getPost('comment-content') ?? '';
        if (!isset($member)) {
            echo "<script>alert('로그인 후 이용가능합니다.');history.back();</script>";
            return;
        }
        if ($comment == '') {
            echo "<script>alert('댓글을 입력하세요.');history.back();</script>";
            return;
        }
        $success = $board->registerComment($seq, $comment, $member->mb_seq);
        if ($success) {
            $this->response->redirect('/read/'.$id.'/'.$page.'/'.$seq);
        } else {
            echo "<script>alert('댓글 등록에 실패하였습니다.');history.back();</script>";
            return;
        }
    }

    function addUp ($id, $seq, $page) {
        helper('session');
        $board = new BoardModel();
        $member = session()->get('user');
        if (!isset($member)) {
            echo "<script>alert('로그인 후 이용가능합니다.');history.back();</script>";
            return;
        }
        $success = $board->addUp($seq, $member->mb_seq);
        if ($success) {
            $this->response->redirect('/read/'.$id.'/'.$page.'/'.$seq);
        } else {
            echo "<script>alert('이미 추천을 눌렀습니다.');history.back();</script>";
            return;
        }
    }
}
