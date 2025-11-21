<?php
require_once 'functions.php';
$user = require_login();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if ($title === '' || $content === '') {
        $error = '标题和内容不能为空';
    } else {
        $doc = [
            'id' => null,
            'title' => $title,
            'content' => $content,
            'author_id' => $user['id'],
            'author_name' => $user['username'],
            'created_at' => date('c'),
            'status' => "normal"
        ];
        if (save_doc($doc)) {
            header('Location: index.php');
            exit;
        } else {
            $error = '保存文档失败';
        }
    }
}
require_once 'templates/header.php';
?>
<div class="card">
  <div class="card-body">
    <h3>创建文档 - <?php echo $title; ?></h3>
    <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">标题</label>
        <input class="form-control" name="title" value="<?= e($_POST['title'] ?? '') ?>" required />
      </div>
      <div class="mb-3">
        <label class="form-label">内容</label>
        <textarea class="form-control" name="content" rows="10" required><?= e($_POST['content'] ?? '') ?></textarea>
      </div>
      <button class="btn btn-primary">发布</button>
    </form>
  </div>
</div>
<?php require_once 'templates/footer.php'; ?>
