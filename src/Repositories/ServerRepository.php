<?php

namespace Kregel\NovaForge\Repositories;

use Carbon\Carbon;
use function foo\func;
use Kregel\NovaForge\Contracts\Repositories\ServerRepositoryContract;
use Kregel\NovaForge\NovaForge;
use Kregel\NovaForge\NovaForgeDataStore;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use Themsaid\Forge\Forge;

class ServerRepository implements ServerRepositoryContract
{
    /**
     * @var \Kregel\DataStore\Contracts\DataStoreContract|\Kregel\DataStore\DataStore
     */
    protected $novaForgeDataStore;

    /**
     * ServerRepository constructor.
     */
    public function __construct()
    {
        $this->novaForgeDataStore = NovaForgeDataStore::forModel(NovaForge::CACHE_MODEL);
    }

    /**
     * @param array $where
     * @param array $with
     * @return array
     */
    public function findAll(array $where = [], array $with = []): array
    {
        $apiKey = $this->novaForgeDataStore->first(NovaForge::CACHE_API_KEY);

        $cacheKey = 'servers';
        // If we already have it stored, return the data.
        if ($this->novaForgeDataStore->exists($cacheKey)) {
            return $this->novaForgeDataStore->get($cacheKey);
        }

        $forge = new Forge($apiKey);

        $with = array_filter($with, function ($part) {
            return $part !== 'servers';
        });

        if (count($with) === 0) {
            return $this->mapData($forge, 'servers');
        }

//        $this->cache($cacheKey, function () use ($forge, $with, $id) {
        return array_flatten(array_map(function ($item) use ($forge) {
            return array_map(function ($server) use ($forge,  $item) {
                return array_merge($server->attributes, $this->mapData($forge, $item, $server->id));
            }, $forge->servers());
        }, $with), 1);
//        });
    }

    /**
     * @param int $id
     * @param array $with
     * @return array
     */
    public function find(int $id, array $with = [])
    {
        $apiKey = $this->novaForgeDataStore->first(NovaForge::CACHE_API_KEY);

        $cacheKey = 'server.' . $id;
        // If we already have it stored, return the data.
        if ($this->novaForgeDataStore->exists($cacheKey)) {
            return $this->novaForgeDataStore->get($cacheKey);
        }

        $forge = new Forge($apiKey);

        $with = array_filter($with, function ($part) {
            return $part !== 'servers';
        });

        if (count($with) === 0) {
            return array_first(array_first($this->mapData($forge, 'server', $id)));
        }

        return $this->cache($cacheKey, [], function () use ($forge, $with, $id): array {
            $server = $forge->server($id);
            return array_first(array_map(function ($item) use ($forge, $id, $server) {
                return array_merge($server->attributes, $this->mapData($forge, $item, $server->id));
            }, $with));
        });
    }

    /**
     * @param $cacheKey
     * @param $with
     * @param \Closure $callback
     * @return array
     * @throws \Exception
     */
    public function cache($cacheKey, $with, \Closure $callback)
    {
        $apiKey = $this->novaForgeDataStore->first(NovaForge::CACHE_API_KEY);

        // If we already have it stored, return the data.
        if ($this->novaForgeDataStore->exists($cacheKey)) {
            return $this->novaForgeDataStore->get($cacheKey);
        }

        $forge = new Forge($apiKey);

        foreach ($with as $item) {
            if (method_exists($forge, $item)) {
                throw new \Exception("Your method {$item} does not exist on the Forge client");
            }
        }

        $this->novaForgeDataStore->save($cacheKey, function () use ($forge, $callback) {
            return $callback($forge);
        });

        return $this->novaForgeDataStore->get($cacheKey);
    }

    /**
     * @param $forge
     * @param $item
     * @param null $id
     * @return array
     */
    protected function mapData($forge, $item, $id = null) {

        $data = $forge->$item($id);

        if (!is_array($data)) {
            $data = [$data];
        }

        return [
            stripos($item, 'server') !== false ? 0 : $item => array_map(function ($datum) use ($item) {
                return $datum->attributes + [
                    'human_created' => Carbon::parse($datum->createdAt)->diffForHumans()
                ];
            }, $data)
        ];
    }
}
