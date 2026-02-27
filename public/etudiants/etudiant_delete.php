<?php
require_once __DIR__ . "/../_init.php";

$id = intval($_GET['id']);
try {
    etudiant_delete($id);
    flash_set('success', 'Étudiant supprimé');
} catch (PDOException $e) {
    flash_set('danger', "Impossible de supprimer l'étudiant, il est peut-être lié à des paiements.");
}

header('Location: etudiants.php');
exit;
