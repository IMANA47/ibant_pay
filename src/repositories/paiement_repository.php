<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/databas.php';

function paiement_create(int $etudiant_id, string $numPaie, float $montant, string $datePaie, string $anneeAc): int
{
	$stmt = db()->prepare("INSERT INTO paiement(etudiant_id, numPaie, montant, datePaie, anneeAc) VALUES(:etudiant_id, :num_paiement, :montant, :date, :annee)");
	$stmt->execute([
		':etudiant_id' => $etudiant_id,
		':num_paiement' => $numPaie,
		':montant' => $montant,
		':date' => $datePaie,
		':annee' => $anneeAc,
	]);
	return (int) db()->lastInsertId();
}

function paiement_update(int $id, int $etudiant_id, string $numPaie, float $montant, string $datePaie, string $anneeAc): void
{
	$stmt = db()->prepare("UPDATE paiement SET etudiant_id = :etudiant_id, numPaie = :num_paiement, montant = :montant, datePaie = :date, anneeAc = :annee WHERE id = :id");
	$stmt->execute([
		':id' => $id,
		':etudiant_id' => $etudiant_id,
		':num_paiement' => $numPaie,
		':montant' => $montant,
		':date' => $datePaie,
		':annee' => $anneeAc,
	]);
}

function paiement_delete(int $id): void
{
	$stmt = db()->prepare("DELETE FROM paiement WHERE id = :id");
	$stmt->execute([':id' => $id]);
}

function paiement_find_by_student(int $etudiant_id): array
{
	$stmt = db()->prepare("SELECT c.*, cl.libelleClasse FROM paiement c JOIN etudiant e ON c.etudiant_id = e.id LEFT JOIN classe cl ON e.idClasse = cl.id WHERE c.etudiant_id = :etudiant_id ORDER BY c.anneeAc DESC");
	$stmt->execute([':etudiant_id' => $etudiant_id]);
	return $stmt->fetchAll();
}

function paiement_find_by_class(int $classe_id): array
{
	$stmt = db()->prepare("SELECT c.*, e.nom AS student_name, e.matriEt FROM paiement c JOIN etudiant e ON c.etudiant_id = e.id WHERE e.idClasse = :classe_id ORDER BY e.nom");
	$stmt->execute([':classe_id' => $classe_id]);
	return $stmt->fetchAll();
}

function paiement_find_by_id(int $id): ?array
{
	$stmt = db()->prepare("SELECT * FROM paiement WHERE id = :id");
	$stmt->execute([':id' => $id]);
	$row = $stmt->fetch();
	return $row ?: null;
}

function paiement_find_all(): array
{
	$stmt = db()->prepare("SELECT p.*, e.nom AS student_name, e.matriEt, cl.libelleClasse FROM paiement p LEFT JOIN etudiant e ON p.etudiant_id = e.id LEFT JOIN classe cl ON e.idClasse = cl.id ORDER BY p.id DESC");
	$stmt->execute();
	return $stmt->fetchAll();
}

function paiement_get_statistics(): array
{
	$today = date('Y-m-d');
	$thisMonth = date('Y-m-01');
	$thisYear = date('Y-01-01');

	$stmt = db()->prepare("
		SELECT 
			(SELECT SUM(montant) FROM paiement WHERE datePaie = :today) AS total_today,
			(SELECT SUM(montant) FROM paiement WHERE datePaie >= :thisMonth) AS total_month,
			(SELECT SUM(montant) FROM paiement WHERE datePaie >= :thisYear) AS total_year
	");
	$stmt->execute([
		':today' => $today,
		':thisMonth' => $thisMonth,
		':thisYear' => $thisYear
	]);

	$res = $stmt->fetch();
	return [
		'today' => (float)($res['total_today'] ?? 0),
		'month' => (float)($res['total_month'] ?? 0),
		'year' => (float)($res['total_year'] ?? 0)
	];
}

function paiement_filter(?string $date, ?string $annee, ?int $classe_id): array
{
    $sql = "SELECT p.*, e.nom AS student_name, e.matriEt, cl.libelleClasse 
            FROM paiement p 
            LEFT JOIN etudiant e ON p.etudiant_id = e.id 
            LEFT JOIN classe cl ON e.idClasse = cl.id 
            WHERE 1=1";
    $params = [];

    if ($date) {
        $sql .= " AND p.datePaie = :date";
        $params[':date'] = $date;
    }
    if ($annee) {
        $sql .= " AND p.anneeAc = :annee";
        $params[':annee'] = $annee;
    }
    if ($classe_id) {
        $sql .= " AND e.idClasse = :classe_id";
        $params[':classe_id'] = $classe_id;
    }

    $sql .= " ORDER BY p.id DESC";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
