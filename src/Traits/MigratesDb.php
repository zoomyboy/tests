<?php

namespace Zoomyboy\Tests\Traits;

use Illuminate\Support\Str;

trait MigratesDb
{
    public $tableNames = [];

    public function runSeeder($seeder)
    {
        (new $seeder)->run();
    }

    public function runMigration($name)
    {
        $this->tableNames[] = $name;
        $this->beforeApplicationDestroyed(function () use ($name) {

            $this->rollbackMigrations();
        });
        $migration = $this->getMigrationInstance($name);
        $migration->up();
    }

    public function rollbackMigrations()
    {
        foreach(array_reverse($this->tableNames) as $migration) {
            $migration = $this->getMigrationInstance($migration);
            $migration->down();
        }

        $this->tableNames = [];
    }

    public function getMigrationInstance($name)
    {
        $migrator = $this->app->make('migrator');
        $files = $migrator->getMigrationFiles(base_path('database/migrations'));
        $migrator->requireFiles($files);
        $file = array_filter($files, function ($file) use ($name) {
            return Str::endsWith($file, $name.'.php');
        });
        if (!count($file)) {
            throw new \Exception('Migration '.$name .' nicht gefunden.');
        }
        $file = array_shift($file);
        return $migrator->resolve($migrator->getMigrationName($file));
    }
}
