<?php

namespace App\Models;

use CodeIgniter\Model;

class BoardModel extends Model
{
    function getBoardName ($url) {
        $db = db_connect();
        $sql = 'select ca_name from category where ca_url = ?';
        $result = $db->query($sql, [$url])->getRow('ca_name');
        return $result;
    }

    function getBoardWrite ($id) {
        $db = db_connect();
        $sql = 'select ca_write from category where ca_url = ?';
        $result = $db->query($sql, [$id])->getRow('ca_write');
        return $result;
    }

    function getPost ($url, $page, $kind, $search) {
        $params = [];
        $db = db_connect();
        $sql = 'SELECT a.bc_seq, a.bc_title, IFNULL(d.up, 0) up, a.bc_read, a.bc_create, b.ca_url, a.ca_seq, c.mb_nick, IFNULL(t.cmt, 0) cmt,';
        $sql .= ' CASE WHEN LEFT(bc_create, 10) = LEFT(NOW(), 10) THEN DATE_FORMAT(bc_create, "%H:%i") ELSE DATE_FORMAT(bc_create, "%m.%d %H:%i") END created';
        $sql .= ' FROM board a';
        $sql .= ' JOIN category b ON a.ca_seq = b.ca_seq';
        $sql .= ' JOIN member c ON a.mb_seq = c.mb_seq';
        $sql .= ' LEFT OUTER JOIN ';
        $sql .= ' (';
        $sql .= '     SELECT bc_seq, COUNT(cm_seq) cmt FROM comment';
        $sql .= ' ) t ON t.bc_seq = a.bc_seq';
        $sql .= ' LEFT OUTER JOIN ';
        $sql .= ' (';
        $sql .= '	  SELECT COUNT(*) up, bc_seq FROM board_up GROUP BY bc_seq';
        $sql .= ' ) d ON a.bc_seq = d.bc_seq';
        $sql .= ' WHERE b.ca_url = ? AND bc_delete = 0';
        $params[] = $url;
        if ($search != '') {
            if ($kind == 1) {
                $sql .= ' AND a.bc_title LIKE "%'.$search.'%"';
            } else {
                $sql .= ' AND c.mb_nick LIKE "%'.$search.'%"';
            }
        }
        $sql .= ' ORDER BY bc_create DESC LIMIT ?, 20';
        $params[] = (($page - 1) * 20);
        $result = $db->query($sql, $params)->getResult('array');
        $count = $db->query($sql, $params)->getNumRows();
        
        $params2 = [];
        $sql2 = 'SELECT bc_seq';
        $sql2 .= ' FROM board a';
        $sql2 .= ' JOIN category b ON a.ca_seq = b.ca_seq';
        $sql2 .= ' JOIN member c ON a.mb_seq = c.mb_seq';
        $sql2 .= ' WHERE b.ca_url = ?';
        $params2[] = $url;
        if ($search != '') {
            if ($kind == 1) {
                $sql2 .= ' AND a.bc_title LIKE "%'.$search.'%"';
            } else {
                $sql2 .= ' AND c.mb_nick LIKE "%'.$search.'%"';
            }
        }
        $totalCount = $db->query($sql2, $params2)->getNumRows();
        $totalPage = ceil($totalCount / 20);

        $data["data"] = $result;
        $data["count"] = $count;
        $data["totalPage"] = $totalPage;
        $data["totalCount"] = $totalCount;
        $data["id"] = $url;
        $data["page"] = $page;
        $data["kind"] = $kind;
        $data["search"] = $search;
        return $data;
    }

    function read ($id, $page, $seq) {
        $db = db_connect();
        helper('session');
        $sessionKey = 'post_view_'.$id.'-'.$seq;
        if (!session()->has($sessionKey)) {
            $sql = 'UPDATE board SET bc_read = bc_read + 1 WHERE bc_seq = ?';
            $db->query($sql, [$seq]);
            session()->set($sessionKey, true);
        }
        $sql = 'SELECT a.bc_seq, b.mb_nick, b.mb_exp, a.mb_seq, b.mb_profile, COUNT(c.cm_seq) cmt_cnt, a.bc_content, a.bc_title, a.bc_read, IFNULL(d.up, 0) up,';
        $sql .= ' CASE WHEN LEFT(bc_create, 10) = LEFT(NOW(), 10) THEN DATE_FORMAT(bc_create, "%H:%i") ELSE DATE_FORMAT(bc_create, "%m.%d %H:%i") END created';
        $sql .= ' FROM board a';
        $sql .= ' JOIN member b ON a.mb_seq = b.mb_seq';
        $sql .= ' LEFT OUTER';
        $sql .= ' JOIN comment c ON a.bc_seq = c.bc_seq';
        $sql .= ' LEFT OUTER JOIN';
        $sql .= ' (';
        $sql .= '     SELECT COUNT(*) up, bc_seq FROM board_up GROUP BY bc_seq';
        $sql .= ' ) d ON a.bc_seq = d.bc_seq';
        $sql .= ' WHERE a.bc_seq = ? AND a.bc_delete = 0';
        $result1 = $db->query($sql, [$seq])->getRow();

        $sql = 'SELECT a.mb_seq, b.mb_nick, b.mb_profile, b.mb_exp, a.cm_comment,';
        $sql .= ' CASE WHEN LEFT(cm_create, 10) = LEFT(NOW(), 10) THEN DATE_FORMAT(cm_create, "%H:%i") ELSE DATE_FORMAT(cm_create, "%m.%d %H:%i") END created';
        $sql .= ' FROM comment a';
        $sql .= ' LEFT OUTER JOIN member b ON a.mb_seq = b.mb_seq';
        $sql .= ' WHERE bc_seq = ? order by cm_create desc';
        $result2 = $db->query($sql, [$seq])->getResult('array');

        $data['content'] = $result1;
        $data['comment'] = $result2;

        return $data;
    }

    function write($data) {
        helper('session');
        $member = session()->get('user');
        if (!isset($member)) {
            echo "<script>alert('로그인 후 이용가능합니다.');history.back();</script>";
            return;
        }
        $db = db_connect();
        $sql = "INSERT INTO board (ca_seq, mb_seq, bc_title, bc_content)";
        $sql .= " SELECT ca_seq, ?, ?, ? FROM category WHERE ca_url = ?";
        $db->query($sql, [$member->mb_seq, $data['title'], $data['content'], $data['id']]);

        return ($db->affectedRows() > 0);
    }

    function modify($data) {
        helper('session');
        $member = session()->get('user');
        if (!isset($member)) {
            echo "<script>alert('로그인 후 이용가능합니다.');history.back();</script>";
            return;
        }
        $db = db_connect();
        $sql = 'select mb_seq from board where bc_seq = ?';
        $writer = $db->query($sql, [$data['seq']])->getRow('mb_seq');
        if ($writer != $member->mb_seq) {
            echo "<script>alert('잘못된 접근입니다.');history.back();</script>";
            return;
        }
        $sql = 'SELECT bc_seq, bc_title, bc_content FROM board WHERE bc_seq = ? and mb_seq = ? and bc_delete = 0';
        $result = $db->query($sql, [$data['seq'], $member->mb_seq])->getRow();
        return $result;
    }

    function deleteBoard ($seq) {
        helper('session');
        $member = session()->get('user');
        if (!isset($member)) {
            echo "<script>alert('로그인 후 이용가능합니다.');history.back();</script>";
            return;
        }
        $db = db_connect();
        $sql = 'select mb_seq from board where bc_seq = ?';
        $writer = $db->query($sql, [$seq])->getRow('mb_seq');
        if ($writer != $member->mb_seq) {
            echo "<script>alert('잘못된 접근입니다.');history.back();</script>";
            return;
        }
        $sql = 'UPDATE board SET bc_delete = 1 WHERE bc_seq = ? AND mb_seq = ?';
        $db->query($sql, [$seq, $member->mb_seq]);
        return ($db->affectedRows() > 0);
    }

    function registerModify ($seq, $title, $content) {
        helper('session');
        $member = session()->get('user');
        if (!isset($member)) {
            echo "<script>alert('로그인 후 이용가능합니다.');history.back();</script>";
            return;
        }
        $db = db_connect();
        $sql = 'select mb_seq from board where bc_seq = ?';
        $writer = $db->query($sql, [$seq])->getRow('mb_seq');
        if ($writer != $member->mb_seq) {
            echo "<script>alert('잘못된 접근입니다.');history.back();</script>";
            return;
        }
        $sql = 'UPDATE board SET bc_title = ?, bc_content = ? WHERE bc_seq = ? AND mb_seq = ?';
        $db->query($sql, [$title, $content, $seq, $member->mb_seq]);
        return ($db->affectedRows() > 0);
    }

    function registerComment ($seq, $comment, $member) {
        $db = db_connect();
        $sql = 'INSERT INTO COMMENT (mb_seq, bc_seq, cm_comment) VALUES(?, ?, ?)';
        $db->query($sql, [$member, $seq, $comment]);
        return ($db->affectedRows() > 0);
    }

    function addUp ($seq, $member) {
        $db = db_connect();
        try {
            $sql = 'INSERT INTO board_up (bc_seq, mb_seq) VALUES(?, ?)';
            $db->query($sql, [$seq, $member]);
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            // 중복 키 오류 처리
            if ($e->getCode() == 1062) {
                // 중복 키 관련 처리
            } else {
                // 다른 종류의 데이터베이스 오류 처리
            }
        }
        return ($db->affectedRows() > 0);
    }
}
