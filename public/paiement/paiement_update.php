<?php
require_once __DIR__ . "/../_init.php";

csrf_check();

$id = intval($_POST['id'] ?? 0);
$data = [
    'etudiant_id' => $_POST['etudiant_id'] ?? 0,
    'numPaie' => $_POST['numPaie'] ?? '',
    'montant' => $_POST['montant'] ?? 0,
    'datePaie' => $_POST['datePaie'] ?? '',
    'anneeAc' => $_POST['anneeAc'] ?? ''
];

[$ok, $msg] = paiement_update_from_array($id, $data);

if ($ok) flash_set('success', $msg);
else flash_set('danger', $msg);

header('Location: paiements.php');
exit;
