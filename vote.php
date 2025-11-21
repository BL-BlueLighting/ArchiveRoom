<?php
require_once 'functions.php';
$user = require_login(); // 登录才能投票
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}
$docid = $_POST['id'] ?? '';
$vote = intval($_POST['vote'] ?? 0);
if (!in_array($vote, [-1,0,1], true)) $vote = 0;
$doc = load_doc($docid);
if (!$doc) {
    header('Location: index.php'); exit;
}
// 设置投票
set_vote($docid, $user['id'], $vote);
// 重定向回查看页
header('Location: view.php?id=' . urlencode($docid));
exit;
