<?php
require_once 'functions.php';
$docid = $_GET['id'] ?? '';
$doc = load_doc($docid);
if (!$doc) {
    header('Location: index.php');
    exit;
}
$user = isset($_SESSION['user_id']) ? get_user_by_id($_SESSION['user_id']) : null;
$summary = compute_vote_summary($docid);
$userVote = 0;
if ($user) {
    $votes = $summary['raw'];
    $uid = strval($user['id']);
    if (isset($votes[$uid])) $userVote = intval($votes[$uid]);
}

if ($doc ["status"] == "normal") { $doc ["status-color"] = "blue"; $doc ["status"] = "正常"; }
elseif ($doc ["status"] == "waiting-for-delete") { $doc ["status-color"] = "red"; $doc ["status"] = "待删除"; } 
elseif ($doc ["status"] == "deleted") { $doc ["status-color"] = "gray"; $doc ["status"] = "已删除"; }


require_once 'templates/header.php';
?>
<div class="row">
  <div class="col-md-8">
    <h2><?= e($doc['title']) ?></h2>
    <div class="text-muted small">By <?= e($doc['author_name']) ?> · <?= e(date('Y-m-d H:i', strtotime($doc['created_at']))) ?></div>
    <hr>
    <div class="card">
      <div class="card-body">
        <pre style="white-space:pre-wrap; word-wrap:break-word;" id="markd"></pre>
      </div>
    </div>

    <?php if ($user && $user['id'] === ($doc['author_id'] ?? null)): ?>
      <form method="post" action="delete.php" onsubmit="return confirm('确认无效化该实体？');">
        <input type="hidden" name="id" value="<?= e($doc['id']) ?>" />
        <button class="btn btn-danger mt-2">无效化该实体</button>
      </form>
    <?php endif; ?>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5>投票 - VOTE FOR THIS DOCUMENT.</h5>
        <div class="mb-2">
          <div>分数: <strong><?= intval($summary['votenum']) ?></strong></div>
          <div>Up: <?= $summary['up'] ?> · Down: <?= $summary['down'] ?></div>
        </div>

        <?php if ($user): ?>
          <div class="d-flex gap-2">
            <form method="post" action="vote.php">
              <input type="hidden" name="id" value="<?= e($doc['id']) ?>" />
              <input type="hidden" name="vote" value="<?= $userVote === 1 ? 0 : 1 ?>" />
              <button class="btn <?= $userVote === 1 ? 'btn-success' : 'btn-outline-success' ?>">▲</button>
            </form>
            <form method="post" action="vote.php">
              <input type="hidden" name="id" value="<?= e($doc['id']) ?>" />
              <input type="hidden" name="vote" value="<?= $userVote === -1 ? 0 : -1 ?>" />
              <button class="btn <?= $userVote === -1 ? 'btn-danger' : 'btn-outline-danger' ?>">▼</button>
            </form>
          </div>
        <?php else: ?>
          <div>请先 <a href="login.php">登录</a> 后投票。</div>
        <?php endif; ?>

        <br/>

        <h5>相关信息 - INFORMATIONS</h5>
        <span class="status status-<?= $doc['status-color']?>">
          <span class="status-dot status-dot-animated"></span>
          <?= $doc['status']?>
        </span>
      </div>
    </div>
  </div>
</div>
<script>
  var textContent = `<?= e($doc['content']) ?>`;
  document.getElementById("markd").innerHTML = new showdown.Converter().makeHtml(textContent);
</script>

<?php require_once 'templates/footer.php'; ?>
