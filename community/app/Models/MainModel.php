<?php

namespace App\Models;

use CodeIgniter\Model;

class MainModel extends Model
{
    function duplicateId ($id) {
        $db = db_connect();
        $sql = 'SELECT COUNT(mb_seq) AS CNT FROM member WHERE mb_id = ?';
        $result = $db->query($sql, [$id]);
        return $result->getRow('CNT');
    }

    function duplicateNick ($nick) {
        $db = db_connect();
        $sql = 'SELECT COUNT(MB_SEQ) AS CNT FROM member WHERE mb_nick = ?';
        $result = $db->query($sql, [$nick]);
        return $result->getRow('CNT');
    }

    function register ($data) {
        $db = db_connect();
        $regHp = '/^(02.{0}|01.{1}|0[1-9][0-9])[-]{0,1}([0-9]+)[-]{0,1}([0-9]{4})$/';
        if (!preg_match($regHp, $data['mobile'])) {
            $result['success'] = false;
            $result['message'] = '올바르지 않은 휴대폰 번호 형식입니다.';
            echo json_encode($result);
        } else {
            $sql = 'SELECT COUNT(*) CNT FROM member WHERE mb_mobile = ?';
            $query = $db->query($sql, [$data['mobile']]);
            $result = $query->getRow('CNT');
            if ($result == 0) {
                $sql = 'INSERT INTO member(mb_pw, mb_id, mb_name, mb_nick, mb_mobile)';
                $sql .= ' VALUES(md5(?), ?, ?, ?, ?)';
                $db->query($sql, [$data['pw'], $data['id'], $data['name'], $data['nick'], $data['mobile']]);
                $final_result['success'] = true;
                $final_result['message'] = '회원가입이 완료되었습니다.';
            } else {
                $final_result['success'] = false;
                $final_result['message'] = '이미 가입된 회원정보가 있습니다.';
            }
            echo json_encode($final_result);
        }
    }

    function getCategory() {
        $db = db_connect();
        $sql = 'SELECT ca_seq, ca_name, ca_write, ca_url FROM category WHERE ca_enable = 0 AND ca_delete = 0 ORDER BY ca_order ASC';
        $result = $db->query($sql)->getResult('array');
        return $result;
    }

    function getRanking () {
        $db = db_connect();
        $sql = 'SELECT a.bc_title, b.ca_url, b.ca_name, a.bc_read, a.bc_seq, IFNULL(c.up, 0) up';
        $sql .= ' FROM board a';
        $sql .= ' JOIN category b ON a.ca_seq = b.ca_seq';
        $sql .= ' LEFT OUTER JOIN';
        $sql .= ' (';
        $sql .= '     SELECT COUNT(*) up, bc_seq FROM board_up GROUP BY bc_seq';
        $sql .= ' ) c ON a.bc_seq = c.bc_seq';
        $sql .= ' WHERE bc_delete = 0 AND a.ca_seq <> 4 AND b.ca_enable = 0';
        $sql .= ' ORDER BY up DESC, bc_read DESC LIMIT 20';
        $result = $db->query($sql)->getResult('array');
        return $result;
    }

    function getNotice () {
        $db = db_connect();
        $sql = 'SELECT a.bc_title, a.ca_seq, b.ca_url, a.bc_read, a.bc_seq';
        $sql .= ' FROM board a';
        $sql .= ' JOIN category b ON a.ca_seq = b.ca_seq';
        $sql .= ' WHERE a.ca_seq = 4';
        $sql .= ' ORDER BY bc_create DESC LIMIT 5';
        $result = $db->query($sql)->getResult('array');
        return $result;
    }

    function user () {
        helper('session');
        $member = session()->get('user');
        if (isset($member)) {
            $db = db_connect();
            $sql = 'SELECT mb_seq, mb_name, mb_nick, mb_id, mb_exp, mb_profile, mb_pw, mb_permission FROM member WHERE mb_seq = ?';
            $result = $db->query($sql, $member->mb_seq)->getRow();
            session()->set('user', $result);
        } else {
            return;
        }
    }
}
