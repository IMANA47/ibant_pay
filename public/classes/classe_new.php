<?php
require_once __DIR__ . "/../_init.php";
require __DIR__ . '/../_header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm border-top border-4 border-danger">
            <div class="card-header bg-white py-3">
                <h2 class="h4 mb-0 fw-bold text-danger"><i class="fa-solid fa-school-flag me-2"></i>Créer une nouvelle classe</h2>
            </div>
            <div class="card-body p-4">
                <form method="post" action="classe_save.php">
                    <?= csrf_token_field() ?>

                    <div class="mb-3">
                        <label class="fw-bold form-label">Identifiant (Code)</label>
                        <input name="codeClasse" class="form-control" placeholder="Ex: IDA" required>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold form-label">Libellé complet</label>
                        <input name="libelleClasse" class="form-control" placeholder="Ex: Informatique de Gestion" required>
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-3">
                        <a href="classes.php" class="btn btn-outline-secondary px-4">Annuler</a>
                        <button class="btn btn-danger px-4">Créer la classe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>