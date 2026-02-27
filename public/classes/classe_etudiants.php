<?php
require_once __DIR__ . "/../_init.php";

$id = intval($_GET['id'] ?? 0);
$classe = classe_find_by_id($id);

if (!$classe) {
    flash_set('danger', 'Classe introuvable.');
    header('Location: classes.php');
    exit;
}

// Fetch students for this class
$stmt = db()->prepare("SELECT * FROM etudiant WHERE idClasse = :id ORDER BY nom");
$stmt->execute([':id' => $id]);
$etudiants = $stmt->fetchAll();

require __DIR__ . '/../_header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="fw-bold"><i class="fa-solid fa-graduation-cap text-danger me-2"></i><?= htmlspecialchars($classe['libelleClasse']) ?></h2>
        <p class="text-muted">Liste des étudiants inscrits dans cette section (Code: <?= htmlspecialchars($classe['codeClasse']) ?>)</p>
    </div>
    <div class="col-md-4 text-md-end">
        <div class="p-3 bg-danger text-white rounded shadow-sm d-inline-block">
            <div class="small text-white-50">Effectif Total</div>
            <div class="h3 mb-0 fw-bold"><?= count($etudiants) ?></div>
        </div>
    </div>
</div>

<?php if (empty($etudiants)): ?>
    <div class="card border-0 shadow-sm p-5 text-center">
        <div class="display-1 text-muted mb-3"><i class="fa-solid fa-users-slash"></i></div>
        <h4 class="text-muted">Aucun étudiant inscrit</h4>
        <p class="mb-4">Il semble qu'aucun étudiant n'ait encore été affecté à cette classe.</p>
        <div>
            <a href="../etudiants/etudiant_new.php" class="btn btn-danger">+ Inscrire un étudiant</a>
            <a href="classes.php" class="btn btn-outline-secondary">Retour</a>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Étudiant</th>
                        <th>Matricule</th>
                        <th>Email</th>
                        <th class="text-end pe-4">Profil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etudiants as $index => $e): ?>
                        <tr class="align-middle">
                            <td class="ps-4"><span class="badge bg-secondary"><?= $index + 1 ?></span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <?php if (!empty($e['photo'])): ?>
                                            <img src="../uploads/<?= htmlspecialchars($e['photo']) ?>" alt="Photo" class="rounded-circle border" width="45" height="45" style="object-fit:cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle border bg-light d-flex align-items-center justify-content-center text-muted" style="width:45px; height:45px; font-size:18px;">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($e['nom']) ?></div>
                                </div>
                            </td>
                            <td><code class="text-danger fw-bold"><?= htmlspecialchars($e['matriEt']) ?></code></td>
                            <td class="text-muted small"><?= htmlspecialchars($e['mail']) ?></td>
                            <td class="text-end pe-4">
                                <a href="../etudiants/etudiants.php?q=<?= urlencode($e['matriEt']) ?>" class="btn btn-sm btn-outline-danger">
                                    Voir Fiche <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        <a href="classes.php" class="btn btn-secondary px-4"><i class="fa-solid fa-chevron-left me-2"></i>Retour aux classes</a>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../_footer.php'; ?>
