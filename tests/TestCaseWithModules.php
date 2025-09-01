<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nwidart\Modules\Facades\Module;

abstract class TestCaseWithModules extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Load Laravel's default migrations
        $this->loadLaravelMigrations();

        // Load all module migrations (like Expenses, etc.)
        foreach (Module::all() as $module) {
            $this->loadMigrationsFrom(module_path($module->getName(), 'Database/Migrations'));
        }

        // $this->loadMigrationsFrom(base_path('Modules/Expenses/Database/Migrations'));
    }
}
