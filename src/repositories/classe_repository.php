<?php

declare(strict_types=1);

require_once __DIR__ . "/../../config/databas.php";

function classe_find_all(): array
{
    $stmt = db()->query("SELECT * FROM classe ORDER BY id DESC");
    return $stmt->fetchAll();
}

function classe_find_by_id(int $id): ?array
{
    $stmt = db()->prepare("SELECT * FROM classe WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function classe_create(string $codeClasse, string $libelleClasse): int
{
    $stmt = db()->prepare("
        INSERT INTO classe(codeClasse, libelleClasse)
        VALUES(:codeClasse, :libelleClasse)
    ");
    $stmt->execute([
        ':codeClasse' => $codeClasse,
        ':libelleClasse' => $libelleClasse,

    ]);
    return (int) db()->lastInsertId();
}

function classe_update(int $id, string $codeClasse, string $libelleClasse): void
{
    $stmt = db()->prepare("
        UPDATE classe
        SET codeClasse = :codeClasse, libelleClasse = :libelleClasse
        WHERE id = :id
    ");
    $stmt->execute([
        ':id' => $id,
        ':codeClasse' => $codeClasse,
        ':libelleClasse' => $libelleClasse,

    ]);
}

function classe_delete(int $id): void
{
    $stmt = db()->prepare("DELETE FROM classe WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

function classe_search(string $keyword): array
{
    $stmt = db()->prepare("SELECT * FROM classe WHERE libelleClasse LIKE :kw OR codeClasse LIKE :kw ORDER BY id DESC");
    $like = "%" . $keyword . "%";
    $stmt->execute([':kw' => $like]);
    return $stmt->fetchAll();
}
