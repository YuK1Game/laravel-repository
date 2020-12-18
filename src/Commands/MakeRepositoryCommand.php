<?php

namespace YuK1\LaravelRepository\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name : Create your repository} {--model : Create model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    protected $type = 'Repository';

    protected function getStub()
    {
        return __DIR__.'/stubs/repository.stub';
    }

    protected function getPath($name)
    {
        $nameInput = $this->getNameInput();
        $nameBase = $this->getBaseName($name);
        return "app/Repositories/$nameBase/$nameInput.php";
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Repositories';
    }

    protected function buildClass($name)
    {
        $replaces = [
            'DummyBaseName' => $this->getBaseName($name),
            'DummyModelNamespace' => $this->getModelNamespace($this->getBaseName($name)),
        ];
        return str_replace(array_keys($replaces), array_values($replaces), parent::buildClass($name));     
    }

    protected function getBaseName($name) {
        if (preg_match('/^.*\\\\(.*?)Repository$/', $name, $matches) === 1) {
            return $matches[1];
        }
        throw new \Exception('Invalid repository name.');  
    }

    protected function getModelsPath() {
        return config('repository.model.path', app()->version() > 8 ? 'app/Models' : 'app');
    }

    protected function getModelsNamespace() {
        return collect(preg_split('/\/|\\\\/', $this->getModelsPath()))
            ->map(function($path) {
                return Str::studly($path);
            })->join('\\');
    }

    protected function getModelNamespace(string $model) {
        return sprintf('%s\\%s', $this->getModelsNamespace(), Str::studly($model));
    }

    public function handle() {
        $result = parent::handle();

        $baseName = str_replace('Repository', '', $this->getNameInput());

        if ($this->option('model') || config('repository.model.auto_create')) {
            $this->call('make:model', ['name' => $baseName ]);
        }
        
        return $result;
    }
}
