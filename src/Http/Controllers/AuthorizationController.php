<?php

namespace Kregel\NovaForge\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Kregel\NovaForge\NovaForge;
use Kregel\NovaForge\NovaForgeDataStore;

class AuthorizationController
{
    use ValidatesRequests;

    protected $novaForgeDataStore;

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            NovaForge::CACHE_API_KEY => 'required'
        ]);

        $this->novaForgeDataStore = NovaForgeDataStore::forModel(NovaForge::CACHE_MODEL);

        if ($this->novaForgeDataStore->exists(NovaForge::CACHE_API_KEY)) {
            $this->novaForgeDataStore->destroy(NovaForge::CACHE_API_KEY);
        }

        $this->novaForgeDataStore->save(NovaForge::CACHE_API_KEY, function () use ($request) {
            return trim($request->get(NovaForge::CACHE_API_KEY));
        });

        return response()->json([
            'message' => 'API Key Saved',
            'exists' => $this->novaForgeDataStore->exists(NovaForge::CACHE_API_KEY)
        ]);
    }
}
