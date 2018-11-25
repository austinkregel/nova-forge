<?php

namespace Kregel\NovaForge\Repositories;

use Carbon\Carbon;
use Kregel\NovaForge\Contracts\Repositories\SiteRepositoryContract;
use Kregel\NovaForge\NovaForge;
use Kregel\NovaForge\NovaForgeDataStore;
use Themsaid\Forge\Forge;

class SiteRepository implements SiteRepositoryContract
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

    public function findAll(int $serverId, array $where = [], array $with = []): array
    {

        $apiKey = $this->novaForgeDataStore->first(NovaForge::CACHE_API_KEY);

        $cacheKey = 'server.' . $serverId . '.sites';
        // If we already have it stored, return the data.
        if ($this->novaForgeDataStore->exists($cacheKey)) {
            return $this->novaForgeDataStore->get($cacheKey);
        }

        $forge = new Forge($apiKey);

        $with = array_filter($with, function ($part) {
            return $part !== 'servers';
        });

        return $this->cache($cacheKey, [], function () use ($forge, $with, $siteId, $serverId): array {
            return array_map(function ($site) {
                return $site->attributes;
            }, $forge->sites($serverId));
        });
    }

    public function find(int $serverId, int $siteId, array $with = [])
    {
        $apiKey = $this->novaForgeDataStore->first(NovaForge::CACHE_API_KEY);

        $cacheKey = 'server.' . $serverId . '.id.' .$siteId;
        // If we already have it stored, return the data.
        if ($this->novaForgeDataStore->exists($cacheKey)) {
            return $this->novaForgeDataStore->get($cacheKey);
        }

        $forge = new Forge($apiKey);

        $with = array_filter($with, function ($part) {
            return $part !== 'servers';
        });

        return $this->cache($cacheKey, [], function () use ($forge, $with, $siteId, $serverId): array {
            return $forge->site($serverId, $siteId)->attributes;
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
    protected function mapData(Forge $forge, $item, $id = null, $otherId = null) {
        if (empty($otherId)) {
            $data = $forge->$item($id);
        } else {
            $data = $forge->$item($id, $otherId);
        }


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
