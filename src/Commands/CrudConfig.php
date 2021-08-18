<?php

namespace asimshazad\simplepanel\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CrudConfig extends Command
{
    protected $signature = 'crud:config {model}';
    protected $description = 'Create CRUD config file.';
    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle()
    {
        // create dir if doesn't exist
        $config_path = 'config/crud';
        if (!$this->files->exists($config_path)) {
            $this->files->makeDirectory($config_path, 0755, true);
        }

        //Ability to override config.stub file
        //It also provides compatibility checks with previous versions of the package.
        $config_stubs_patch = config('asimshazad.crud_paths.stubs') . '/config.stub';
        if (!$this->files->exists($config_stubs_patch)) {
            $config_stubs_patch = __DIR__ . '/../../resources/stubs/crud/config.stub';
        }

        // create crud config file
        $config_file = $config_path . '/' . $this->argument('model') . 'Config.php';
        $this->files->copy($config_stubs_patch, $config_file);
        $this->line('Config file created: <info>' . $config_file . '</info>');
    }
}
