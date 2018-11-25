<?php

namespace Kregel\NovaForge;

use Kregel\DataStore\DataStore;
use Kregel\NovaForge\Contracts\NovaForgeDataStoreContract;

class NovaForgeDataStore extends DataStore implements NovaForgeDataStoreContract
{
    /**
     * @const string
     */
    public const PACKAGE_TAG = 'kregel.forge-cache';
}
