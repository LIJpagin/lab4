<?php
require_once __DIR__ . '/../helpers.php';

$db = getConnect();
$author = $db->query("DELETE FROM publications WHERE id = '".$_GET['id']."'");
redirect('/');

?>