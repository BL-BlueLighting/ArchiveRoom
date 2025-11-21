<?php
require_once 'functions.php';
require_once 'templates/header.php';

$docs = list_docs();
?>
<div class="row">
  <div class="col-12">
    <h1><?php echo $title; ?></h1>
    <p class="text-muted">欢迎回到 <?php echo $title; ?>，继续您的工作，研究员。</p>
    <div class="list-group">
      <?php foreach ($docs as $doc): 
        $summary = compute_vote_summary($doc['id']);
      ?>
        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="view.php?id=<?= e($doc['id']) ?>">
          <div>
            <div class="fw-bold"><?= e($doc['title']) ?></div>
            <div class="text-muted small">By <?= e($doc['author_name'] ?? '匿名') ?> · <?= e(date('Y-m-d H:i', strtotime($doc['created_at']))) ?></div>
          </div>
          <div class="text-end">
            <div class="small text-muted">Score: <span class="fw-semibold"><?= intval($summary['votenum']) ?></span></div>
            <div class="small text-muted">Up: <?= $summary['up'] ?> Down: <?= $summary['down'] ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php require_once 'templates/footer.php'; ?>
