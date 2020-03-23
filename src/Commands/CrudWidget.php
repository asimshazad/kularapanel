<?php

namespace Khludev\KuLaraPanel\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class CrudWidget extends Command
{
    protected $signature = 'crud:widget {model} {--force}';
    protected $description = 'Generate Widget based on your config file.';
    protected $files;
    protected $config;
    protected $replaces = [];

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle()
    {
        // ensure config file exists
        $config_file = 'config/crud/' . $this->argument('model') . '.php';
        if (!$this->files->exists($config_file)) {
            $this->error('Config file not found: <info>' . $config_file . '</info>');
            return;
        }

        // set class values
        $this->config = include $config_file;
        $this->lap = config('lap.crud_paths');
        $this->setSimpleReplaces();

        // generate crud
        $this->line('Generating <info>' . $this->argument('model') . '</info> Widget...');
        $this->makeDirectories();
        $this->createWidgetFile();
        $this->createViewFile();
        $this->line('Widget generation for <info>' . $this->argument('model') . '</info> complete!');
    }

    public function setSimpleReplaces()
    {

        // set simple replacement searches for stubs
        $this->replaces = [
            '{widgets_namespace}' => $controller_namespace = ltrim(config('lap.widgets_namespace'),'\\'),
            '{model_namespace}' => $model_namespace = ucfirst(str_replace('/', '\\', $this->lap['model'])),
            '{model_class}' => $model_class = $this->argument('model'),
            '{model_string}' => $model_string = trim(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $model_class)),
            '{model_strings}' => $model_strings = str_plural($model_string),
            '{model_variable}' => $model_variable = strtolower(str_replace(' ', '_', $model_string)),
            '{model_variables}' => $model_variables = strtolower(str_replace(' ', '_', $model_strings)),
            '{view_prefix_url}' => $view_prefix_url = ltrim(str_replace('resources/views', '', $this->lap['views']) . '/', '/'),
            '{view_prefix_name}' => $view_prefix_name = str_replace('/', '.', $view_prefix_url),
        ];
    }

    public function replace($content)
    {
        // replace all occurrences with $this->replaces
        foreach ($this->replaces as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        return $content;
    }

    public function makeDirectories()
    {
        // create directories recursively if they don't already exist
        $directories = [
            config('lap.widgets_path'),
            $this->lap['views'] . '/' . $this->replaces['{model_variables}'] . '/datatable',
        ];

        foreach ($directories as $directory) {
            if (!$this->files->exists($directory)) {
                $this->files->makeDirectory($directory, 0755, true);
            }
        }
    }

    public function createViewFile()
    {
        // create view files
        $view_path = $this->lap['views'] . '/' . $this->replaces['{model_variables}'];
        foreach ($this->files->allFiles($this->lap['stubs'] . '/views/models') as $file) {
            if ($file->getFilename() == 'widget.stub') {
                $new_file = $view_path . '/' . ltrim($file->getRelativePath() . '/' . str_replace('.stub', '.blade.php', $file->getFilename()), '/');
                if ($this->prompt($new_file)) {
                    $this->files->put($new_file, $this->replace($file->getContents()));
                    $this->line('View files created: <info>' . $new_file . '</info>');
                }
            }
        }
    }

    public function createWidgetFile()
    {
        // create widget file
        $widget_file = config('lap.widgets_path') . '/' . $this->replaces['{model_class}'] . 'Widget.php';
        if ($this->prompt($widget_file)) {
            $widget_stub = $this->files->get($this->lap['stubs'] . '/widget.stub');
            $this->files->put($widget_file, $this->replace($widget_stub));
            $this->line('Widget file created: <info>' . $widget_file . '</info>');
        }
    }

    public function indent($multiplier = 1)
    {
        // add indents to line
        return str_repeat('    ', $multiplier);
    }

    public function putInChains($value)
    {
        // convert string to chains using methods and parameters
        $chains = [];

        foreach (explode('|', $value) as $chain) {
            $method_params = explode(':', $chain);
            $method = $method_params[0];
            $params_typed = [];

            // add quotes to parameter if not boolean or numeric
            if (isset($method_params[1])) {
                foreach (explode(',', $method_params[1]) as $param) {
                    $params_typed[] = (in_array($param, ['true', 'false']) || is_numeric($param)) ? $param : "'$param'";
                }
            }

            $chains[] = $method . '(' . implode(', ', $params_typed) . ')';
        }

        return implode('->', $chains);
    }

    public function flattenArray($array)
    {
        $flat = [];

        foreach ($array as $key => $value) {
            $flat[] = "'$key' => " . $this->quoteVar($value);
        }

        return '[' . implode(', ', $flat) . ']';
    }

    public function quoteVar($value)
    {
        return is_bool($value) || is_numeric($value) ? var_export($value, true) : "'$value'";
    }

    protected function prompt($file)
    {
        if ($this->option('force')) {
            return true;
        }
        $this->info($file);
        if (file_exists($file)) {
            if (!$this->confirm('Overwrite? At your own RISK!',false)) {
                return false;
            }
        } else {
            if (!$this->confirm('Create?',true)) {
                return false;
            }
        }
        return true;
    }
}
