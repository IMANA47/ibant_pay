<?php
require_once __DIR__ . "/../_init.php";

csrf_check();

$id = intval($_POST['id']);
$matricule = trim($_POST['matricule']);
$nom = trim($_POST['nom']);
$email = trim($_POST['email']);
$classe = intval($_POST['classe']);

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
} else {
    // Keep old photo
    $et = etudiant_find_by_id($id);
    $photoPath = $et['photo'] ?? null;
}

etudiant_update($id, $matricule, $nom, $email, $photoPath, $classe);

flash_set('success', 'Étudiant mis à jour');
header('Location: etudiants.php');
exit;
