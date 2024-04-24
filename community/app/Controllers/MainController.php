<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MainModel;

class MainController extends BaseController
{
    public function index()
    {
        $main = new MainModel();
        $data['categories'] = $main->getCategory();
        $sub['ranking'] = $main->getRanking();
        $sub['rankingCount'] = count($sub['ranking']);
        $sub['notices'] = $main->getNotice();
        $sub['noticeCount'] = count($sub['notices']);
        $data['content'] = view('home', $sub);
        $data['title'] = '이름없음';
        return view('index', $data);
    }

    function login () {
        $main = new MainModel();
        $data['title'] = '이름없음';
        $data['categories'] = $main->getCategory();
        $data['content'] = view('login');
        return view('index', $data);
    }

    function duplicateId () {
        $register = new MainModel();
        $id = $this->request->getPost('id');
        $result = $register->duplicateId($id);
        return $result;
    }

    function duplicateNick () {
        $register = new MainModel();
        $nick = $this->request->getPost('nick');
        $result = $register->duplicateNick($nick);
        return $result;
    }

    function register () {
        $main = new MainModel();
        $data['title'] = '이름없음';
        $data['categories'] = $main->getCategory();
        $data['content'] = view('register');
        return view('index', $data);
    }

    function doRegister () {
        $main = new MainModel();
        $data = [
            'id' => $this->request->getPost('id'),
            'pw' => $this->request->getPost('pw'),
            'nick' => $this->request->getPost('nick'),
            'name' => $this->request->getPost('name'),
            'mobile' => $this->request->getPost('mobile')
        ];
        $result = $main->register($data);
        return $result;
    }

    function doLogin () {
        $main = new MainModel();
        $data = [
            'id' => $this->request->getPost('login-id'),
            'pw' => $this->request->getPost('login-pw'),
        ];
        helper('session');
        $db = db_connect();
        $sql = 'select mb_seq from member where mb_id = ?';
        $id = $db->query($sql, [$data['id']])->getNumRows();
        if ($id > 0) {
            $sql = 'select md5(?) as pass';
            $chkpass = $db->query($sql, [$data['pw']])->getRow('pass');
            $sql = 'SELECT mb_seq, mb_name, mb_nick, mb_id, mb_exp, mb_profile, mb_pw, mb_permission FROM member WHERE mb_id = ?';
            $user = $db->query($sql, [$data['id']])->getRow();

            if ($chkpass == $user->mb_pw) {
                $delete = 'mb_pw';
                if (property_exists($user, $delete)) {
                    unset($user->$delete);
                }
                session()->set('user', $user);
                $this->response->redirect('/');
            } else {
                echo '<script>alert("비밀번호가 틀렸습니다.");history.back();</script>';
                exit;
            }
        } else {
            echo '<script>alert("존재하지 않은 회원입니다.");history.back();</script>';
            exit;
        }
    }

    function logout () {
        helper('session');
        session()->remove('user');
        $this->response->redirect('/');
    }
}
