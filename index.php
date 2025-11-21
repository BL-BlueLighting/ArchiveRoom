<?php
require_once 'functions.php';
require_once 'templates/header.php';

$docs = list_docs();
$docsC = count($docs);
$users = count(get_users());

?>
<div class="row">
    <div class="col-12">
        <h1><?php echo $title; ?></h1>
        <p class="text-muted">欢迎回到 <?php echo $title; ?>，继续您的工作，研究员。</p>
        <div class="datagrid">
            <div class="datagrid-item">
                <div class="datagrid-title">文章</div>
                <div class="datagrid-content"><?php echo $docsC; ?> 篇</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">用户</div>
                <div class="datagrid-content"><?php echo $users; ?> 人</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">最后更新</div>
                <div class="datagrid-content"><?php echo date('Y-m-d H:i:s', filemtime('./data/docs/updatefile')); ?></div>
            </div>

        </div>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>
