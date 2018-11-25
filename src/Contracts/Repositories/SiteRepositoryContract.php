<?php

namespace Kregel\NovaForge\Contracts\Repositories;

interface SiteRepositoryContract
{
    public function findAll(int $serverId, array $where = [], array $with = []): array;

    public function find(int $serverId, int $siteId, array $with = []);
}