<?php
require_once __DIR__ . '/../helpers.php';

$db = getConnect();

if (isset($_POST['publication_ids'])) {
    foreach ($_POST['publication_ids'] as $publication_id) {
        $db->query("DELETE FROM publications WHERE id = '".$db->real_escape_string($publication_id)."'");
    }
    redirect('/');
} else {
    redirect('/');
}
?>