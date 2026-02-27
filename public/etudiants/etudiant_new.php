<?php
require_once __DIR__ . "/../_init.php";

$classes = classe_find_all();
require __DIR__ . '/../_header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm border-top border-4 border-danger">
            <div class="card-header bg-white py-3">
                <h2 class="h4 mb-0 fw-bold text-danger"><i class="fa-solid fa-user-plus me-2"></i>Inscrire un nouvel étudiant</h2>
            </div>
            <div class="card-body p-4">
                <form method="post" action="etudiant_save.php" enctype="multipart/form-data">
                    <?= csrf_token_field() ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold form-label">Matricule</label>
                            <input name="matricule" class="form-control" placeholder="Ex: IBANT001" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold form-label">Nom complet</label>
                            <input name="nom" class="form-control" placeholder="Nom et Prénom" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="etudiant@example.com">
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold form-label">Photo de profil</label>
                        <input type="file" name="photo" accept="image/*" class="form-control">
                        <div class="form-text">Formats acceptés : JPG, PNG, GIF.</div>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold form-label">Classe d'affectation</label>
                        <select name="classe" class="form-select" required>
                            <option value="">-- Choisir une classe --</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['libelleClasse']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-3">
                        <a href="etudiants.php" class="btn btn-outline-secondary px-4">Annuler</a>
                        <button class="btn btn-danger px-4">Enregistrer l'étudiant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>