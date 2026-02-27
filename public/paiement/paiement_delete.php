<?php
require_once __DIR__ . "/../_init.php";

$id = intval($_GET['id']);
paiement_delete($id);

flash_set('success', 'Paiement supprimée');
header('Location: paiements.php');
exit;
