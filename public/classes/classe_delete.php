<?php
require_once __DIR__ . "/../_init.php";

$id = intval($_GET['id'] ?? 0);

try {
    classe_delete($id);
    flash_set('success', 'Classe supprimée');
} catch (PDOException $e) {
    flash_set('danger', "Impossible de supprimer la classe, elle contient probablement des étudiants ou des paiements.");
}

header('Location: classes.php');
exit;
