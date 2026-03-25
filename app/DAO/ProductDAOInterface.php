<?php

namespace App\DAO;

interface ProductDAOInterface
{
    public function getAll();
    public function findBySlug(string $slug);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}