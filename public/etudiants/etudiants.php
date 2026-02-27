<?php
require_once __DIR__ . "/../_init.php";

$q = trim($_GET['q'] ?? '');
$etudiants = $q ? etudiant_search($q) : etudiant_find_all();
$classes = classe_find_all();

require __DIR__ . '/../_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-6">Étudiants</h1>
        <p class="text-muted">Gérer le registre des étudiants</p>
    </div>

    <div class="d-flex">
        <form class="d-flex" method="get" action="etudiants.php">
            <input name="q" class="form-control me-2" type="search" placeholder="Rechercher..."
                value="<?= htmlspecialchars($q) ?>" style="width:220px">
            <button class="btn btn-outline-success btn-sm">Chercher</button>
        </form>

        <a href="etudiant_new.php" class="btn btn-danger ms-3">+ Nouvel étudiant</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Photo</th>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Classe</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($etudiants as $index => $e): ?>
                <tr class="align-middle">
                    <td><span class="badge bg-secondary"><?= $index + 1 ?></span></td>
                    <td>
                        <?php if (!empty($e['photo'])): ?>
                            <img src="../uploads/<?= htmlspecialchars($e['photo']) ?>" alt="Photo" class="rounded-circle border" width="40" height="40" style="object-fit:cover;">
                        <?php else: ?>
                            <div class="rounded-circle border bg-light d-flex align-items-center justify-content-center text-muted" style="width:40px; height:40px; font-size:12px;">N/A</div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($e['matriEt']) ?></strong></td>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                    <td><?= htmlspecialchars($e['mail']) ?></td>
                    <td><?= htmlspecialchars($e['libelleClasse'] ?? '-') ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <!-- Bouton Voir -->
                            <button class="btn btn-sm btn-danger text-white" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#viewModal<?= $e['id'] ?>" title="Voir mes données">
                                <i class="fa-solid fa-eye"></i> Voir
                            </button>

                            <!-- Bouton Modifier (Ouvre la Modale) -->
                            <button class="btn btn-sm btn-outline-success" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal<?= $e['id'] ?>" title="Modifier">
                                <i class="fa-solid fa-pen"></i> Modifier
                            </button>

                            <!-- Bouton Supprimer -->
                            <button class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal<?= $e['id'] ?>" title="Supprimer">
                                <i class="fa-solid fa-trash"></i> Supprimer
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- MODALE VOIR DÉTAILS -->
                <div class="modal fade" id="viewModal<?= $e['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title"><i class="fa-solid fa-user-graduate me-2"></i>Détails de l'Étudiant</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center p-4">
                                <div class="mb-3">
                                    <?php if (!empty($e['photo'])): ?>
                                        <img src="../uploads/<?= htmlspecialchars($e['photo']) ?>" alt="Photo" class="rounded-circle border shadow-sm" width="120" height="120" style="object-fit:cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle border bg-light d-inline-flex align-items-center justify-content-center text-muted shadow-sm" style="width:120px; height:120px; font-size:40px;">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <h3 class="fw-bold mb-1"><?= htmlspecialchars($e['nom']) ?></h3>
                                <p class="text-muted fs-5 mb-4">Matricule : <span class="badge bg-danger"><?= htmlspecialchars($e['matriEt']) ?></span></p>
                                
                                <ul class="list-group list-group-flush text-start">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fa-solid fa-envelope me-2 text-muted"></i>Email</span>
                                        <span class="fw-bold"><?= htmlspecialchars($e['mail'] ?: 'Non renseigné') ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fa-solid fa-school me-2 text-muted"></i>Classe</span>
                                        <span class="badge bg-primary rounded-pill"><?= htmlspecialchars($e['libelleClasse'] ?? 'Non affecté') ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="modal-footer bg-light">
                                <button class="btn btn-danger w-100" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODALE ÉDITER -->
                <div class="modal fade" id="editModal<?= $e['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title"><i class="fa-solid fa-user-pen me-2"></i>Modifier l'Étudiant</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="post" action="etudiant_update.php" enctype="multipart/form-data">
                                <div class="modal-body p-4">
                                    <?= csrf_token_field() ?>
                                    <input type="hidden" name="id" value="<?= $e['id'] ?>">
                                    
                                    <div class="text-center mb-3">
                                        <?php if (!empty($e['photo'])): ?>
                                            <img src="../uploads/<?= htmlspecialchars($e['photo']) ?>" class="rounded border mb-2" width="60" height="60" style="object-fit:cover;">
                                        <?php endif; ?>
                                        <input type="file" name="photo" accept="image/*" class="form-control form-control-sm">
                                        <div class="form-text">Changer la photo (optionnel)</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold form-label">Matricule</label>
                                        <input name="matricule" class="form-control" value="<?= htmlspecialchars($e['matriEt']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold form-label">Nom complet</label>
                                        <input name="nom" class="form-control" value="<?= htmlspecialchars($e['nom']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($e['mail']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold form-label">Classe</label>
                                        <select name="classe" class="form-select" required>
                                            <?php foreach ($classes as $c): ?>
                                                <option value="<?= $c['id'] ?>" <?= $c['id'] == $e['idClasse'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($c['libelleClasse']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light px-4">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-danger px-4">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MODALE SUPPRESSION -->
                <div class="modal fade" id="deleteModal<?= $e['id'] ?>" tabindex="-1">
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
                                    Voulez-vous vraiment supprimer l’étudiant<br>
                                    <strong class="fs-4"><?= htmlspecialchars($e['nom']) ?></strong> ?
                                </p>
                                <p class="text-muted small mt-2">Cette action supprimera également son historique de paiements.</p>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <a href="etudiant_delete.php?id=<?= $e['id'] ?>" class="btn btn-danger px-4">Oui, supprimer</a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>