<?php

namespace Kregel\NovaForge\Contracts\Repositories;

interface ServerRepositoryContract
{
    public function findAll(array $where = [], array $with = []): array;

    public function find(int $id, array $with = []);
}
