<?php

namespace Superconductor\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

//#[AsCommand('make:tool', "Creates an MCP Tool handled by Superconductor")]
class MakeToolCommand extends GeneratorCommand
{
    protected $signature = 'make:tool {name} {--description= : Description of the tool}';

    protected $description = "Creates an MCP Tool handled by Superconductor";

    protected $type = 'Agent tool';

    public function handle(): void
    {
        parent::handle();

        $this->rewriteToSyncTool();
    }

    protected function rewriteToSyncTool(): void
    {
        $name = $this->qualifyClass($this->getNameInput());
        $us_name = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->getNameInput()));
        $path = $this->getPath($name);
        $content = file_get_contents($path);

        // Replace all the tokens
        $content = str_replace('{{ tool_name }}', $us_name, $content);
        $content = str_replace('{{ description }}', $this->getToolDescription(), $content);

        file_put_contents($path, $content);

        $this->components->info("Tool created successfully!");
        $this->components->warn("Don't forget to register the tool in your MCP config:");
        $this->components->bulletList([
            "Add '{$us_name}' => {$name}::class to your config/mcp.php tools array"
        ]);
    }

    protected function getToolDescription(): string
    {
        return $this->option('description') ?: 'The Agent will read this when getting the list.';
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/tool.stub');
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
        return $rootNamespace.'\AiTools';
    }
}
