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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/2.1.0/showdown.min.js"></script>
  </head>
  <body class="antialiased">
    <header class="navbar navbar-expand-md navbar-light d-print-none">
      <div class="container-xl">
        <a href="index.php" class="navbar-brand">
          <span class="navbar-brand-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-library"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 10h-3a1 1 0 0 0 0 2h3a1 1 0 0 0 0 -2m3 -3h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m-1 -3h-5a1 1 0 0 0 0 2h5a1 1 0 0 0 0 -2" /><path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" /></svg>
          </span>
          <span class="navbar-brand-text">ArchiveROOM.</span>
        </a>
        <?php if ($currentUser): ?>
          <div class="navbar-nav ms-auto">
            <a class="btn btn-link" ></a>
            <a class="btn btn-link" href="/textlist.php">文档列表</a>
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-reset"
                data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar avatar-sm">AR</span>
                <div class="d-none d-xl-block ps-2">
                  <div><?= e($currentUser['username']) ?></div>
                  <div class="mt-1 small text-secondary"><?= e($currentUser['permission']) ?></div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <a class="dropdown-item" href="create.php">新建文档</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php">注销</a>
              </div>
            </div>
          <?php else: ?>
            <a class="btn btn-primary" href="login.php">登录</a>
            <a class="btn btn-link" href="register.php">注册</a>
          <?php endif; ?>
        </div>
      </div>
    </header>
    <main class="py-4">
      <div class="container-xl">
