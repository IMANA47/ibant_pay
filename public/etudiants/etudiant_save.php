<?php
require_once __DIR__ . "/../_init.php";

csrf_check();

$matricule = trim($_POST['matricule']);
$nom = trim($_POST['nom']);
$email = trim($_POST['email']);
$classe = intval($_POST['classe']);

if ($matricule === '' || $nom === '') {
    flash_set('danger', 'Matricule et nom requis');
    header('Location: etudiant_new.php');
    exit;
}

$photoPath = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['photo']['tmp_name'];
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('stu_') . '.' . $ext;
    
    $uploadDir = __DIR__ . '/../../public/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    
    if (move_uploaded_file($tmpName, $uploadDir . $filename)) {
        $photoPath = $filename;
    }
}

etudiant_create($matricule, $nom, $email, $photoPath, $classe);
flash_set('success', 'Étudiant créé avec succès');
header('Location: etudiants.php');
exit;
