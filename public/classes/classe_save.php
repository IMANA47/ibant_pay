<?php
require_once __DIR__ . "/../_init.php";
csrf_check();

$codeClasse = trim($_POST['codeClasse'] ?? '');
$libelle  = trim($_POST['libelleClasse'] ?? '');

if ($codeClasse === '' || $libelle === '') {
    flash_set('danger', 'Tous les champs sont requis');
    header('Location: classe_new.php');
    exit;
}

$data = [
    'codeClasse' => $codeClasse,
    'libelleClasse' => $libelle
];

[$ok, $msg, $newId] = classe_save($data);

if ($ok) {
    flash_set('success', $msg);
    header('Location: classes.php');
} else {
    flash_set('danger', $msg);
    header('Location: classe_new.php');
}
exit;
