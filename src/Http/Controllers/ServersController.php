<?php

namespace Kregel\NovaForge\Http\Controllers;

use Illuminate\Http\Request;
use Kregel\NovaForge\Contracts\Repositories\ServerRepositoryContract;

class ServersController extends Controller
{
    /**
     * @var \Kregel\NovaForge\NovaForgeDataStore
     */
    protected $novaForgeDataStore;

    protected $repository;

    public function __construct(ServerRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        return $this->repository->findAll([], [
            'sites'
        ]);
    }

    public function show($serverId)
    {
        return $this->repository->find($serverId, [
            'sites'
        ]);
    }
}
