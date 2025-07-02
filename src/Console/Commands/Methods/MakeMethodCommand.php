<?php

namespace MCP\Console\Commands\Methods;


use Illuminate\Console\GeneratorCommand;

//#[AsCommand('make:method', "Creates an MCP Method handled by Superconductor")]
class MakeMethodCommand extends GeneratorCommand
{
    protected $signature = 'make:method {route} {name}';

    protected $description = "Creates an MCP Method handled by Superconductor";

    protected $type = 'MCP Method';

    public function handle(): void
    {
        parent::handle();
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/method.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . "/../../..{$stub}";
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Rpc\Controllers';
    }
}
