<?php

declare(strict_types=1);

require_once __DIR__ . '/../repositories/etudiant_repository.php';

function etudiant_save_from_array(array $data, ?string $photoPath = null): array
{
	$mat = trim($data['matriEt'] ?? '');
	$nom = trim($data['nom'] ?? '');
	$email = trim($data['email'] ?? '');
	$idClasse = isset($data['idClasse']) && $data['idClasse'] !== '' ? (int)$data['idClasse'] : null;
	if ($mat === '' || $nom === '') return [false, 'Matricule et nom requis.'];
	$newId = etudiant_create($mat, $nom, $email, $photoPath, $idClasse);
	return [true, 'Etudiant créé.', $newId];
}

function etudiant_update_from_array(int $id, array $data, ?string $photoPath = null): array
{
	$p = etudiant_find_by_id($id);
	if (!$p) return [false, 'Etudiant introuvable.'];
	$mat = trim($data['matriEt'] ?? $p['matriEt']);
	$nom = trim($data['nom'] ?? $p['nom']);
	$email = trim($data['email'] ?? ($p['mail'] ?? ($p['email'] ?? '')));
	$idClasse = isset($data['idClasse']) && $data['idClasse'] !== '' ? (int)$data['idClasse'] : $p['idClasse'];
	etudiant_update($id, $mat, $nom, $email, $photoPath, $idClasse);
	return [true, 'Etudiant mis à jour.'];
}
