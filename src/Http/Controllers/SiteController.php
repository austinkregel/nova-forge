<?php

namespace Kregel\NovaForge\Http\Controllers;

use Kregel\NovaForge\Contracts\Repositories\SiteRepositoryContract;

class SiteController
{
    /**
     * @var SiteRepositoryContract
     */
    protected $repository;

    public function __construct(SiteRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function index($serverId, $siteId)
    {
        return $this->repository->find($serverId, $siteId);
    }
}
