<?php
require_once 'functions.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    if ($username === '' || $password === '') {
        $error = '用户名和密码不能为空';
    } elseif ($password !== $password2) {
        $error = '两次密码不一致';
    } elseif (user_exists($username)) {
        $error = '用户名已存在';
    } else {
        $user = add_user($username, $password);
        if ($user) {
            login_user($user);
            header('Location: index.php');
            exit;
        } else {
            $error = '注册失败（写入错误）';
        }
    }
}
require_once 'templates/header.php';
?>
<div class="card">
  <div class="card-body">
    <h3>新研究员登记 - <?php echo $title; ?></h3>
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
      <div class="mb-3">
        <label class="form-label">确认密码</label>
        <input class="form-control" type="password" name="password2" required />
      </div>
      <button class="btn btn-primary">登记</button>
    </form>
  </div>
</div>
<?php require_once 'templates/footer.php'; ?>
