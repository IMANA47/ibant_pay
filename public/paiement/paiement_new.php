<?php
require_once __DIR__ . "/../_init.php";

$etudiants = etudiant_find_all();

require __DIR__ . '/../_header.php';
?>

<h2>Nouvelle Paiement</h2>

<form method="post" action="paiement_save.php">
    <?= csrf_token_field() ?>

    <label class="fw-bold">Étudiant</label>
    <select name="etudiant" class="form-select mb-3" required>
        <option value="">-- Sélectionner --</option>
        <?php foreach ($etudiants as $e): ?>
            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nom']) ?> (<?= $e['matriEt'] ?>)</option>
        <?php endforeach; ?>
    </select>

    <label class="fw-bold">Montant</label>
    <input type="number" name="montant" class="form-control mb-3" min="0" step="0.01" required>

    <label class="fw-bold">Date de Paiement</label>
    <input type="date" name="datePaie" class="form-control mb-3" value="<?= date('Y-m-d') ?>" required>

    <label class="fw-bold">Numéro de Reçu</label>
    <input name="num_paiement" class="form-control mb-3" placeholder="P001" required>

    <label class="fw-bold">Année académique</label>
    <input type="text" name="annee" class="form-control mb-3" placeholder="2024" required>

    <button class="btn btn-success">Enregistrer</button>
    <a href="paiements.php" class="btn btn-secondary">Annuler</a>
</form>

<?php require __DIR__ . '/../_footer.php'; ?>