<?php

namespace Kregel\NovaForge;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaForge extends Tool
{
    public const CACHE_API_KEY = 'forge_key';
    public const CACHE_MODEL = 'laravel-nova-forge';

    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('nova-forge', __DIR__.'/../dist/js/tool.js');
        Nova::style('nova-forge', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('nova-forge::navigation');
    }
}
