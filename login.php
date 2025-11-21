<?php
require_once 'functions.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $user = get_user_by_username($username);
    if (!$user || !password_verify($password, $user['password'])) {
        $error = '用户名或密码错误';
    } else {
        login_user($user);
        header('Location: index.php');
        exit;
    }
}
require_once 'templates/header.php';
?>
<div class="card">
  <div class="card-body">
    <h3>登录 <?php echo $title; ?></h3>
    <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">用户名</label>
        <input class="form-control" name="username" required />
      </div>
      <div class="mb-3">
        <label class="form-label">密码</label>
        <input class="form-control" type="password" name="password" required />
      </div>
      <button class="btn btn-primary">登录</button>
    </form>
  </div>
</div>
<?php require_once 'templates/footer.php'; ?>
