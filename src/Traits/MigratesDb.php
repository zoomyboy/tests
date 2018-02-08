<?php

namespace Zoomyboy\Tests\Traits;

trait MigratesDb
{
    public function runSeeder($seeder)
    {
        (new $seeder)->run();
    }

    public function runMigration($name)
    {
        $this->beforeApplicationDestroyed(function () use ($name) {
            $this->rollbackMigration($name);
        });
        $migration = $this->getMigrationInstance($name);
        $migration->up();
    }

    public function rollbackMigration($name)
    {
        $migration = $this->getMigrationInstance($name);
        $migration->down();
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
