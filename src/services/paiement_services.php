<?php

declare(strict_types=1);

require_once __DIR__ . '/../repositories/paiement_repository.php';

function paiement_save_from_array(array $data): array
{
	$etudiant_id = isset($data['etudiant_id']) ? (int)$data['etudiant_id'] : 0;
	$num_paiement = trim($data['numPaie'] ?? '');
	$date = !empty($data['datePaie']) ? trim($data['datePaie']) : date('Y-m-d');
	$montant = isset($data['montant']) ? (float)$data['montant'] : 0.0;
	$annee = !empty($data['anneeAc']) ? trim($data['anneeAc']) : date('Y');
	if ($etudiant_id <= 0) return [false, 'Etudiant requis.'];
	if ($num_paiement === '') $num_paiement = 'P' . time(); 
	$newId = paiement_create($etudiant_id, $num_paiement, $montant, $date, $annee);
	return [true, 'Paiement ajouté avec succès.', $newId];
}

function paiement_update_from_array(int $id, array $data): array
{
	$etudiant_id = isset($data['etudiant_id']) ? (int)$data['etudiant_id'] : 0;
	$num_paiement = trim($data['numPaie'] ?? '');
	$date = !empty($data['datePaie']) ? trim($data['datePaie']) : date('Y-m-d');
	$montant = isset($data['montant']) ? (float)$data['montant'] : 0.0 ;
	$annee = !empty($data['anneeAc']) ? trim($data['anneeAc']) : date('Y');
	
	paiement_update($id, $etudiant_id, $num_paiement, $montant, $date, $annee);
	return [true, 'Paiement mis à jour.'];
}
