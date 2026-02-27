<?php
require_once __DIR__ . "/../_init.php";

csrf_check();

$id = intval($_POST['id']);

[$ok, $msg] = classe_update_from_array($id, $_POST);

flash_set($ok ? 'success' : 'danger', $msg);
header('Location: classes.php');
exit;
