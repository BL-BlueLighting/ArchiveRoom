<?php
session_start();

// 清除所有 Session
$_SESSION = [];
session_unset();
session_destroy();

// 删除记住登录的 Cookie（若有）
if (isset($_COOKIE['user_token'])) {
    setcookie('user_token', '', time() - 3600, '/');
}

// 跳转到首页或登录页
header("Location: login.php");
exit;
