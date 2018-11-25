<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Kregel\NovaForge\Http\Middleware\AuthenticateForge;
/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::post('authenticate', 'AuthorizationController');

Route::get('/server/{serverId}/site/{siteId}', 'SiteController@index')->middleware(AuthenticateForge::class);

Route::resource('/servers', 'ServersController', [
    'except' => ['create', 'update']
])->middleware(AuthenticateForge::class);

