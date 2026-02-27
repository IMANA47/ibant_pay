<?php
require_once __DIR__ . "/../_init.php";

csrf_check();

$data = [
    'etudiant_id' => $_POST['etudiant'] ?? 0,
    'numPaie' => $_POST['num_paiement'] ?? '',
    'montant' => $_POST['montant'] ?? 0,
    'datePaie' => $_POST['datePaie'] ?? '',
    'anneeAc' => $_POST['annee'] ?? ''
];

[$ok, $msg, $newId] = paiement_save_from_array($data);

if ($ok) flash_set('success', $msg);
else flash_set('danger', $msg);

header('Location: paiements.php');
exit;
