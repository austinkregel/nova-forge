<?php

namespace Kregel\NovaForge\Http\Middleware;

use Illuminate\Validation\ValidationException;
use Kregel\NovaForge\Contracts\NovaForgeDataStoreContract;
use Kregel\NovaForge\NovaForge;
use Kregel\NovaForge\NovaForgeDataStore;

class AuthenticateForge
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        $dataStore = NovaForgeDataStore::forModel(NovaForge::CACHE_MODEL);

        if (!$dataStore->exists(NovaForge::CACHE_API_KEY)) {
            throw ValidationException::withMessages([
                NovaForge::CACHE_API_KEY . '.datastore' => ['In order to use this route, you must set the api key for forge.']
            ]);
        }

        $request->merge([
            NovaForge::CACHE_API_KEY => $dataStore->first(NovaForge::CACHE_API_KEY)
        ]);

        return $next($request);
    }
}
