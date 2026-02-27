<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/databas.php';

function etudiant_find_all(): array
{
	$stmt = db()->query("SELECT e.*, c.libelleClasse FROM etudiant e LEFT JOIN classe c ON e.idClasse = c.id ORDER BY e.id DESC");
	return $stmt->fetchAll();
}

function etudiant_find_by_id(int $id): ?array
{
	$stmt = db()->prepare("SELECT * FROM etudiant WHERE id = :id");
	$stmt->execute([':id' => $id]);
	$row = $stmt->fetch();
	return $row ?: null;
}

function etudiant_create(string $matricule, string $nom, string $email, ?string $photoPath, ?int $idClasse): int
{
	$stmt = db()->prepare("INSERT INTO etudiant(matriEt, nom, mail, photo, idClasse) VALUES(:matricule, :nom, :email, :photo, :idClasse)");
	$stmt->execute([
		':matricule' => $matricule,
		':nom' => $nom,
		':email' => $email,
		':photo' => $photoPath,
		':idClasse' => $idClasse,
	]);
	return (int) db()->lastInsertId();
}

function etudiant_update(int $id, string $matricule, string $nom, string $email, ?string $photoPath, ?int $idClasse): void
{
	$stmt = db()->prepare("UPDATE etudiant SET matriEt = :matricule, nom = :nom, mail = :email, photo = :photo, idClasse = :idClasse WHERE id = :id");
	$stmt->execute([
		':id' => $id,
		':matricule' => $matricule,
		':nom' => $nom,
		':email' => $email,
		':photo' => $photoPath,
		':idClasse' => $idClasse,
	]);
}

function etudiant_delete(int $id): void
{
	$stmt = db()->prepare("DELETE FROM etudiant WHERE id = :id");
	$stmt->execute([':id' => $id]);
}

function etudiant_search(string $keyword): array
{
	$stmt = db()->prepare("SELECT * FROM etudiant WHERE nom LIKE :kw OR matriEt LIKE :kw ORDER BY id DESC");
	$like = "%" . $keyword . "%";
	$stmt->execute([':kw' => $like]);
	return $stmt->fetchAll();
}
