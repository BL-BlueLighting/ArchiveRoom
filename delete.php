<?php
require_once 'functions.php';
$user = require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $doc = load_doc($id);
    if ($doc && intval($doc['author_id']) === intval($user['id'])) {
        delete_doc($id);
    }
}
header('Location: index.php');
exit;
