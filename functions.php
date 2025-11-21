<?php
// functions.php
session_start();

define('DATA_DIR', __DIR__ . '/data');
define('USERS_FILE', DATA_DIR . '/users.json');
define('DOCS_DIR', DATA_DIR . '/docs');
define('VOTES_DIR', DATA_DIR . '/votes');
define('LOG_DIR', __DIR__ . '/logs');
@mkdir(DATA_DIR, 0755, true);
@mkdir(DOCS_DIR, 0755, true);
@mkdir(VOTES_DIR, 0755, true);
@mkdir(LOG_DIR, 0755, true);

// UTILITIES
function read_json_file($path) {
    if (!file_exists($path)) return null;
    $data = file_get_contents($path);
    $obj = json_decode($data, true);
    return $obj;
}

function write_json_file($path, $data) {
    $tmp = $path . '.tmp';
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $fp = fopen($tmp, 'wb');
    if ($fp === false) return false;
    if (!flock($fp, LOCK_EX)) { fclose($fp); return false; }
    fwrite($fp, $json);
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    rename($tmp, $path);
    return true;
}

function user_exists($username) {
    $users = read_json_file(USERS_FILE);
    if (!$users) return false;
    foreach ($users as $u) { if ($u['username'] === $username) return true; }
    return false;
}

function remove_user($username) {
    $users = read_json_file(USERS_FILE) ?: [];
    $new_users = [];
    foreach ($users as $u) { if ($u['username'] !== $username) $new_users[] = $u; }
    return write_json_file(USERS_FILE, $new_users);
}

function get_users() {
    return read_json_file(USERS_FILE) ?: [];
}

function add_user($username, $password) {
    $users = read_json_file(USERS_FILE) ?: [];
    $id = 1;
    if (!empty($users)) {
        $ids = array_column($users, 'id');
        $id = max($ids) + 1;
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $user = [
        'id' => $id,
        'username' => $username,
        'password' => $hash,
        'created_at' => date('c'),
    ];
    $users[] = $user;
    return write_json_file(USERS_FILE, $users) ? $user : false;
}

function get_user_by_username($username) {
    $users = read_json_file(USERS_FILE) ?: [];
    foreach ($users as $u) { if ($u['username'] === $username) return $u; }
    return null;
}

function get_user_by_id($id) {
    $users = read_json_file(USERS_FILE) ?: [];
    foreach ($users as $u) { if ($u['id'] == $id) return $u; }
    return null;
}

function login_user($user) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    // 登录日志
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $line = date('c') . " | user_id={$user['id']} username={$user['username']} ip={$ip}\n";
    file_put_contents(LOG_DIR . '/logins.log', $line, FILE_APPEND | LOCK_EX);
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    $user = get_user_by_id($_SESSION['user_id']);
    if (!$user) {
        session_destroy();
        header('Location: login.php');
        exit;
    }
    return $user;
}

// DOCS
function generate_doc_id() {
    // 基于时间戳+随机
    return 'doc-' . time() . '-' . bin2hex(random_bytes(4));
}

function save_doc($doc) {
    if (empty($doc['id'])) $doc['id'] = generate_doc_id();
    $path = DOCS_DIR . '/' . $doc['id'] . '.json';
    $doc['updated_at'] = date('c');
    file_put_contents("./data/docs/updatefile", "updated");
    return write_json_file($path, $doc);
}

function load_doc($docid) {
    $path = DOCS_DIR . '/' . $docid . '.json';
    return read_json_file($path);
}

function list_docs() {
    $files = glob(DOCS_DIR . '/*.json');
    $docs = [];
    foreach ($files as $f) {
        $d = read_json_file($f);
        if ($d) $docs[] = $d;
    }
    // 按创建时间 desc
    usort($docs, function($a, $b){ return strtotime($b['created_at']) - strtotime($a['created_at']); });
    return $docs;
}

function delete_doc($docid) {
    $f1 = DOCS_DIR . '/' . $docid . '.json';
    $f2 = VOTES_DIR . '/' . $docid . '.json';
    @unlink($f1);
    @unlink($f2);
    return true;
}

// VOTES
function load_votes_for_doc($docid) {
    $path = VOTES_DIR . '/' . $docid . '.json';
    $data = read_json_file($path);
    return $data ?: [];
}

function save_votes_for_doc($docid, $votes) {
    $path = VOTES_DIR . '/' . $docid . '.json';
    return write_json_file($path, $votes);
}

// 返回 votenum（总分）和 counts
function compute_vote_summary($docid) {
    $votes = load_votes_for_doc($docid);
    $total = 0;
    $up = 0; $down = 0;
    foreach ($votes as $uid => $v) {
        $total += intval($v);
        if ($v == 1) $up++;
        if ($v == -1) $down++;
    }
    return ['votenum' => $total, 'up' => $up, 'down' => $down, 'raw' => $votes];
}

function set_vote($docid, $userid, $vote) {
    // $vote: 1 up, -1 down, 0 remove
    $votes = load_votes_for_doc($docid);
    $userid = strval($userid);
    if ($vote === 0) {
        if (isset($votes[$userid])) unset($votes[$userid]);
    } else {
        $votes[$userid] = intval($vote);
    }
    return save_votes_for_doc($docid, $votes);
}

// Helpers
function e($s) { return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
