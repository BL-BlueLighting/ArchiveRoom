<?php
// templates/header.php
if (!session_id()) session_start();
$currentUser = isset($_SESSION['user_id']) ? get_user_by_id($_SESSION['user_id']) : null;

include 'config.php';
?>
<!doctype html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?php echo $title; ?></title>
    <!-- Tabler CDN -->
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" />
  </head>
  <body class="antialiased">
    <header class="navbar navbar-expand-md navbar-light d-print-none">
      <div class="container-xl">
        <a href="index.php" class="navbar-brand">
          <span class="navbar-brand-icon">
            <svg width="24" height="24" viewBox="0 0 24 24"><path d="M3 12h18"></path></svg>
          </span>
          <span class="navbar-brand-text">ArchiveROOM.</span>
        </a>
        <div class="navbar-nav ms-auto">
          <?php if ($currentUser): ?>
            <span class="nav-link">你好，<?= e($currentUser['username']) ?></span>
            <a class="btn btn-outline-primary" href="create.php">发布文档</a>
            <a class="btn btn-link" href="logout.php">退出</a>
          <?php else: ?>
            <a class="btn btn-primary" href="login.php">登录</a>
            <a class="btn btn-link" href="register.php">注册</a>
          <?php endif; ?>
        </div>
      </div>
    </header>
    <main class="py-4">
      <div class="container-xl">
