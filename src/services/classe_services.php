<?php

declare(strict_types=1);

require_once __DIR__ . "/../repositories/classe_repository.php";

function classe_save(array $data): array
{
    $codeClasse = trim($data['codeClasse'] ?? '');
    $libelle = trim($data['libelleClasse'] ?? '');
    if ($codeClasse === '' || $libelle === '') {
        return [false, 'Identifiant et libellé requis.'];
    }
    try {
        $newId = classe_create($codeClasse, $libelle);
        return [true, 'Classe créée.', $newId];
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            return [false, "L'identifiant '$codeClasse' existe déjà."];
        }
        return [false, "Erreur interne : " . $e->getMessage()];
    }
}

function classe_edit(int $id, array $data): array
{
    $p = classe_find_by_id($id);
    if (!$p) return [false, 'Classe introuvable.'];
    $codeClasse = trim($data['codeClasse'] ?? $p['codeClasse']);
    $libelle = trim($data['libelleClasse'] ?? $p['libelleClasse']);
    if ($codeClasse === '' || $libelle === '') return [false, 'Identifiant et libellé requis.'];
    classe_update($id, $codeClasse, $libelle);
    return [true, 'Classe mise à jour.'];
}

function classe_remove(int $id): array
{
    $p = classe_find_by_id($id);
    if (!$p) return [false, 'Classe introuvable.'];
    classe_delete($id);
    return [true, 'Classe supprimée.'];
}


function classe_update_from_array(int $id, array $data): array
{
    $classe = classe_find_by_id($id);
    if (!$classe) {
        return [false, "Classe introuvable."];
    }

    $codeClasse = trim($data['codeClasse'] ?? $classe['codeClasse']);
    $libelle = trim($data['libelleClasse'] ?? $classe['libelleClasse']);

    try {
        classe_update($id, $codeClasse, $libelle);
        return [true, "Classe mise à jour avec succès."];

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            return [false, "L'identifiant '$codeClasse' existe déjà."];
        }
        return [false, "Erreur interne : " . $e->getMessage()];
    }
}


