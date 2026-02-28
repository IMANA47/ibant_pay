<?php
require_once __DIR__ . "/../_init.php";


// Récupération des filtres
$f_date = $_GET['f_date'] ?? null;
$f_annee = $_GET['f_annee'] ?? null;
$f_classe = isset($_GET['f_classe']) ? (int)$_GET['f_classe'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  if (isset($_POST['save'])) {
    $data = [
      'etudiant_id' => intval($_POST['etudiant'] ?? 0),
      'numPaie' => trim($_POST['numPaie'] ?? ''),
      'montant' => floatval($_POST['montant'] ?? 0),
      'datePaie' => trim($_POST['datePaie'] ?? ''),
      'anneeAc' => trim($_POST['anneeAc'] ?? '')
    ];
    [$ok, $msg, $newId] = paiement_save_from_array($data);
    if ($ok) flash_set('success', $msg);
    else flash_set('danger', $msg);
    header('Location: paiements.php');
    exit;
  }
}

$etudiants = etudiant_find_all();
$classes = classe_find_all();

// Utilisation du nouveau filtre
$viewPaiement = paiement_filter($f_date, $f_annee, $f_classe);
$stats = paiement_get_statistics();

require __DIR__ . '/../_header.php';
?>
<div class="row align-items-center mb-4">
  <div class="col-md-6">
    <h1 class="display-6 fw-bold text-dark"><i class="fa-solid fa-receipt text-danger me-2"></i>Paiements</h1>
    <p class="text-muted">Suivi des encaissements et statistiques financières</p>
  </div>
  <div class="col-md-6 text-md-end">
    <button class="btn btn-danger shadow-sm" data-bs-toggle="collapse" data-bs-target="#collapseAdd">
      <i class="fa-solid fa-plus me-2"></i>Nouveau Paiement
    </button>
  </div>
</div>

<!-- DASHBOARD STATISTIQUES -->
<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="stat-card stat-card-total shadow-sm">
      <div class="stat-icon"><i class="fa-solid fa-calendar-day"></i></div>
      <div class="position-relative">
        <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Aujourd'hui</h6>
        <h2 class="fw-bold mb-0"><?= number_format($stats['today'], 2) ?> FCFA</h2>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card stat-card-month shadow-sm">
      <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
      <div class="position-relative">
        <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Ce Mois-ci</h6>
        <h2 class="fw-bold mb-0"><?= number_format($stats['month'], 2) ?> FCFA</h2>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card stat-card-year shadow-sm">
      <div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div>
      <div class="position-relative">
        <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Année Académique</h6>
        <h2 class="fw-bold mb-0"><?= number_format($stats['year'], 2) ?> FCFA</h2>
      </div>
    </div>
  </div>
</div>

<!-- FORMULAIRE AJOUT (COLLAPSE) -->
<div class="collapse mb-4" id="collapseAdd">
  <div class="card border-0 shadow-sm border-top border-danger border-4">
    <div class="card-body p-4">
      <h5 class="card-title fw-bold mb-4">Enregistrer un nouveau paiement</h5>
      <form method="post" action="paiements.php">
        <?php echo csrf_token_field(); ?>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label fw-bold">Étudiant</label>
            <select name="etudiant" class="form-select" required>
              <option value="">-- Sélectionner --</option>
              <?php foreach ($etudiants as $e): ?>
                <option value="<?php echo $e['id']; ?>"><?php echo htmlspecialchars($e['nom'] . ' (' . ($e['matriEt'] ?? '') . ')'); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold">Numéro de Reçu</label>
            <input name="numPaie" class="form-control" type="text" placeholder="P001" required>
          </div>
          <div class="col-md-2">
            <label class="form-label fw-bold">Montant (FCFA)</label>
            <input name="montant" class="form-control" type="number" step="0.01" min="0" placeholder="0.00" required>
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold">Date</label>
            <input name="datePaie" class="form-control" type="date" value="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold">Année Académique</label>
            <input name="anneeAc" class="form-control" type="text" value="<?= date('Y') ?>" required>
          </div>
          <div class="col-md-9 d-flex align-items-end justify-content-end">
            <button name="save" class="btn btn-danger px-4">Enregistrer le paiement</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- FILTRES -->
<div class="card border-0 shadow-sm mb-4 bg-white">
  <div class="card-body p-3">
    <form method="get" action="paiements.php" class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label small fw-bold text-muted mb-1">Date précise</label>
        <input type="date" name="f_date" class="form-control form-control-sm" value="<?= htmlspecialchars($f_date ?? '') ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-bold text-muted mb-1">Année Académique</label>
        <input type="text" name="f_annee" class="form-control form-control-sm" placeholder="Ex: 2024" value="<?= htmlspecialchars($f_annee ?? '') ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-bold text-muted mb-1">Classe</label>
        <select name="f_classe" class="form-select form-select-sm">
          <option value="">Toutes les classes</option>
          <?php foreach ($classes as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $f_classe == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['libelleClasse']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-danger btn-sm flex-grow-1"><i class="fa-solid fa-filter me-1"></i>Filtrer</button>
        <a href="paiements.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-rotate-left"></i></a>
      </div>
    </form>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th>Étudiant</th>
        <th>Classe</th>
        <th>Montant</th>
        <th class="text-center">Numéro Reçu</th>
        <th>Date</th>
        <th>Année</th>
        <th class="text-end">Actions</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($viewPaiement as $index => $n): ?>
        <tr class="align-middle">
          <td><span class="badge bg-secondary"><?= $index + 1 ?></span></td>
          <td>
            <div class="d-flex align-items-center">
              <div class="avatar-sm bg-light rounded-circle border d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px;">
                <i class="fa-solid fa-user text-muted" style="font-size: 0.8rem;"></i>
              </div>
              <div>
                <div class="fw-bold text-dark"><?= htmlspecialchars($n['student_name'] ?? '-') ?></div>
                <div class="small text-muted"><?= htmlspecialchars($n['matriEt'] ?? '') ?></div>
              </div>
            </div>
          </td>
          <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($n['libelleClasse'] ?? '-') ?></span></td>
          <td><span class="fw-bold text-success"><?= number_format($n['montant'], 2) ?> FCFA</span></td>
          <td class="text-center"><code class="text-danger fw-bold"><?= htmlspecialchars($n['numPaie'] ?? '') ?></code></td>
          <td><?= date('d/m/Y', strtotime($n['datePaie'])) ?></td>
          <td><?= htmlspecialchars($n['anneeAc'] ?? '') ?></td>
          <td class="text-end">
            <div class="d-flex justify-content-end gap-2">
              <button class="btn btn-sm btn-danger text-white" data-bs-toggle="modal" data-bs-target="#viewModal<?= $n['id'] ?>">
                <i class="fa-solid fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#editModal<?= $n['id'] ?>">
                <i class="fa-solid fa-pen"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $n['id'] ?>">
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
          </td>
        </tr>

        <!-- MODALE VOIR -->
        <div class="modal fade" id="viewModal<?= $n['id'] ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa-solid fa-receipt me-2"></i>Détails du Paiement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body p-4">
                <div class="text-center mb-4">
                  <div class="display-5 fw-bold text-success"><?= number_format($n['montant'], 2) ?> FCFA</div>
                  <div class="text-muted">Reçu N° <?= htmlspecialchars($n['numPaie']) ?></div>
                </div>
                <hr>
                <div class="row g-3">
                  <div class="col-6">
                    <label class="text-muted small text-uppercase fw-bold">Étudiant</label>
                    <div class="fw-bold"><?= htmlspecialchars($n['student_name']) ?></div>
                  </div>
                  <div class="col-6">
                    <label class="text-muted small text-uppercase fw-bold">Matricule</label>
                    <div class="fw-bold"><?= htmlspecialchars($n['matriEt']) ?></div>
                  </div>
                  <div class="col-6">
                    <label class="text-muted small text-uppercase fw-bold">Classe</label>
                    <div class="fw-bold"><?= htmlspecialchars($n['libelleClasse']) ?></div>
                  </div>
                  <div class="col-6">
                    <label class="text-muted small text-uppercase fw-bold">Date</label>
                    <div class="fw-bold"><?= date('d F Y', strtotime($n['datePaie'])) ?></div>
                  </div>
                  <div class="col-12">
                    <label class="text-muted small text-uppercase fw-bold">Année Académique</label>
                    <div class="fw-bold"><?= htmlspecialchars($n['anneeAc']) ?></div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-danger w-100" data-bs-dismiss="modal">Fermer</button>
              </div>
            </div>
          </div>
        </div>

        <!-- MODALE ÉDITER -->
        <div class="modal fade" id="editModal<?= $n['id'] ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa-solid fa-file-pen me-2"></i>Modifier le Paiement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <form method="post" action="paiement_update.php">
                <div class="modal-body p-4">
                  <?= csrf_token_field() ?>
                  <input type="hidden" name="id" value="<?= $n['id'] ?>">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Étudiant</label>
                    <select name="etudiant_id" class="form-select" required>
                      <?php foreach ($etudiants as $e): ?>
                        <option value="<?= $e['id'] ?>" <?= $e['id'] == $n['etudiant_id'] ? 'selected' : '' ?>>
                          <?= htmlspecialchars($e['nom']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="form-label fw-bold">Montant (FCFA)</label>
                      <input type="number" step="0.01" name="montant" class="form-control" value="<?= $n['montant'] ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="form-label fw-bold">Numéro Reçu</label>
                      <input type="text" name="numPaie" class="form-control" value="<?= htmlspecialchars($n['numPaie']) ?>" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="form-label fw-bold">Date</label>
                      <input type="date" name="datePaie" class="form-control" value="<?= $n['datePaie'] ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="form-label fw-bold">Année Académique</label>
                      <input type="text" name="anneeAc" class="form-control" value="<?= htmlspecialchars($n['anneeAc']) ?>" required>
                    </div>
                  </div>
                </div>
                <div class="modal-footer bg-light">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" class="btn btn-danger px-4">Sauvegarder</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- MODALE SUPPRESSION -->
        <div class="modal fade" id="deleteModal<?= $n['id'] ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-danger">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body p-4 text-center">
                <i class="fa-regular fa-circle-xmark text-danger d-block mb-3" style="font-size: 3rem;"></i>
                <p class="fs-5">Voulez-vous vraiment supprimer le paiement de <strong><?= number_format($n['montant'], 2) ?> FCFA</strong> pour l'étudiant <strong><?= htmlspecialchars($n['student_name']) ?></strong> ?</p>
                <p class="text-muted small">N° Reçu : <?= htmlspecialchars($n['numPaie']) ?></p>
              </div>
              <div class="modal-footer bg-light">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="paiement_delete.php?id=<?= $n['id'] ?>" class="btn btn-danger px-4">Oui, supprimer</a>
              </div>
            </div>
          </div>
        </div>

      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
