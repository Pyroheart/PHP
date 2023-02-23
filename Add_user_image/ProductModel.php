<?php

declare(strict_types=1);

class ProductModel
{
    private PDO $db;

    // On injecte une instance de PDO pour le bon fonctionnement du model
    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function findByName(string $name): bool|array
    {
        $q = $this->db->prepare('SELECT id FROM product WHERE name = :name');
        $q->execute(['name' => $name]);
        return $q->fetch();
    }

    public function insert(array $params)
    {
        $q = $this->db->prepare(
            'INSERT INTO product(name, price, picture)
            VALUES (:name, :price, :picture)'
        );
        $q->execute([
            'name' => $params['name'],
            'price' => $params['pha'],
            'picture' => $params['photo']
        ]);
    }
}
