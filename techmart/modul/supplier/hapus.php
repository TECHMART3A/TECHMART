<?php
require_once '../../config/database.php';

$db = getDB();

$id = $_GET['id'] ?? null;

if (!$id || !ctype_digit((string)$id)) {
    die('ID supplier tidak valid!');
}

$stmt = $db->prepare("DELETE FROM supplier WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
?>