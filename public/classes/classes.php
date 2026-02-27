<?php
require_once __DIR__ . "/../_init.php";

$q = trim($_GET['q'] ?? '');
$classes = $q ? classe_search($q) : classe_find_all();

require __DIR__ . '/../_header.php';
?>

<h1>Liste des classes</h1>

<a href="classe_new.php" class="btn btn-success mb-3">+ Nouvelle classe</a>

<form method="get" class="mb-3">
  <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Rechercher..." class="form-control w-25 d-inline">
  <button class="btn btn-danger">Chercher</button>
</form>

<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Identifiant</th>
      <th>Libellé</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($classes as $index => $c): ?>
      <tr>
        <td><span class="badge bg-secondary"><?= $index + 1 ?></span></td>
        <td><?= htmlspecialchars($c['codeClasse']) ?></td>
        <td><?= htmlspecialchars($c['libelleClasse']) ?></td>
        <td>
          <div class="d-flex gap-2">
            <!-- Bouton Voir -->
            <button class="btn btn-sm btn-danger text-white" 
                    data-bs-toggle="modal" 
                    data-bs-target="#viewModal<?= $c['id'] ?>" title="Voir mes données">
              <i class="fa-solid fa-eye"></i> Voir
            </button>
            
            <a href="classe_etudiants.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-secondary text-white" title="Voir les étudiants de cette classe">
              <i class="fa-solid fa-users"></i> Étudiants
            </a>

            <!-- Bouton Modifier (Ouvre la Modale) -->
            <button class="btn btn-sm btn-outline-success" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editModal<?= $c['id'] ?>" title="Modifier">
              <i class="fa-solid fa-pen"></i> Modifier
            </button>

            <!-- Bouton Supprimer -->
            <button class="btn btn-sm btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteModal<?= $c['id'] ?>" title="Supprimer">
              <i class="fa-solid fa-trash"></i> Supprimer
            </button>
          </div>
        </td>
      </tr>

      <!-- MODALE VOIR DÉTAILS -->
      <div class="modal fade" id="viewModal<?= $c['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header bg-info text-white">
              <h5 class="modal-title"><i class="fa-solid fa-school me-2"></i>Détails de la Classe</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
              <div class="display-1 text-info mb-3">
                <i class="fa-solid fa-chalkboard"></i>
              </div>
              <h3 class="fw-bold mb-1"><?= htmlspecialchars($c['libelleClasse']) ?></h3>
              <p class="text-muted fs-5 mb-4">Code : <span class="badge bg-danger"><?= htmlspecialchars($c['codeClasse']) ?></span></p>
              
              <ul class="list-group list-group-flush text-start">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Identifiant Interne (ID)
                  <span class="fw-bold"><?= $c['id'] ?></span>
                </li>
              </ul>
            </div>
            <div class="modal-footer bg-light">
              <button class="btn btn-secondary w-100" data-bs-dismiss="modal">Fermer</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODALE ÉDITER -->
      <div class="modal fade" id="editModal<?= $c['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title"><i class="fa-solid fa-pen-to-square me-2"></i>Modifier la Classe</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="classe_update.php">
              <div class="modal-body p-4">
                <?= csrf_token_field() ?>
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                
                <div class="mb-3">
                  <label class="fw-bold form-label text-muted">Statut interne</label>
                  <input type="text" class="form-control bg-light" value="ID: <?= $c['id'] ?>" disabled>
                </div>

                <div class="mb-3">
                  <label class="fw-bold form-label">Identifiant (Code)</label>
                  <input name="codeClasse" class="form-control" value="<?= htmlspecialchars($c['codeClasse']) ?>" required>
                </div>

                <div class="mb-3">
                  <label class="fw-bold form-label">Libellé</label>
                  <input name="libelleClasse" class="form-control" value="<?= htmlspecialchars($c['libelleClasse']) ?>" required>
                </div>
              </div>
              <div class="modal-footer bg-light px-4">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-success px-4">Enregistrer</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- MODALE SUPPRESSION -->
      <div class="modal fade" id="deleteModal<?= $c['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Confirmer la suppression</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
              <div class="text-danger mb-3" style="font-size: 3rem;">
                <i class="fa-regular fa-circle-xmark"></i>
              </div>
              <p class="fs-5 mb-0">
                Voulez-vous vraiment supprimer la classe<br>
                <strong class="fs-4"><?= htmlspecialchars($c['codeClasse']) ?></strong> ?
              </p>
              <p class="text-muted small mt-2">Cette action est irréversible et supprimera également les étudiants liés.</p>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <a href="classe_delete.php?id=<?= $c['id'] ?>" class="btn btn-danger px-4">Oui, supprimer</a>
            </div>
          </div>
        </div>
      </div>

    <?php endforeach; ?>
  </tbody>
</table>

<?php require __DIR__ . '/../_footer.php'; ?>