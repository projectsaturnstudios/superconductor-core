<?php

namespace MCP\Console\Commands\MCPInspector;

use Illuminate\Console\Command;
use MCP\Sessions\ClientSession;
use MCP\Support\Facades\MCP;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('mcp:inspect', 'Run MCP Inspector')]
class RunMCPInspectorCommand extends Command
{
    protected $signature = 'mcp:inspect';
    protected $description = 'Interactive MCP Inspector - Connect to and inspect MCP servers';

    public function handle(): void
    {
        $this->displayWelcome();

        $selectedServer = $this->selectMcpServer();

        if ($selectedServer === 'exit') {
            $this->info('👋 Goodbye!');
            return; // Exit early, don't continue with initialization
        }

        $this->info("🚀 Connecting to MCP server: <fg=cyan>{$selectedServer}</>");
        $this->continueWithInitialization($selectedServer);
    }

    protected function displayWelcome(): void
    {
        $this->newLine();
        $this->line('╔══════════════════════════════════════╗');
        $this->line('║         🔍 MCP INSPECTOR             ║');
        $this->line('║    Superconductor Development Tool   ║');
        $this->line('╚══════════════════════════════════════╝');
        $this->newLine();
        $this->info('Welcome to the MCP Inspector! This tool helps you connect to and inspect MCP servers.');
        $this->newLine();
    }

    protected function selectMcpServer(): string
    {
        $servers = config('mcp-servers', []);

        if (empty($servers)) {
            $this->error('❌ No MCP servers configured in config/mcp-servers.php');
            return 'exit';
        }

        $this->info('📡 Available MCP Servers:');
        $this->newLine();

        // Build menu options
        $options = [];
        $displayOptions = [];

        foreach ($servers as $serverName => $serverConfig) {
            $driver = $serverConfig['driver'] ?? 'unknown';
            $command = $serverConfig['command'] ?? $serverConfig['url'] ?? 'N/A';

            $options[] = $serverName;
            $displayOptions[] = sprintf(
                '<fg=green>%s</> <fg=yellow>[%s]</> - %s',
                $serverName,
                $driver,
                $this->truncateString($command, 50)
            );
        }

        // Add exit option
        $options[] = 'exit';
        $displayOptions[] = '<fg=red>Exit</>';

        // Display numbered menu
        foreach ($displayOptions as $index => $display) {
            $this->line(sprintf('  <fg=cyan>%d.</> %s', $index + 1, $display));
        }

        $this->newLine();

        // Get user choice
        $choice = $this->ask('Select an MCP server (enter number or name)', '1');

        // Handle numeric input
        if (is_numeric($choice)) {
            $index = (int)$choice - 1;
            if (isset($options[$index])) {
                return $options[$index];
            }
        }

        // Handle string input
        if (in_array($choice, $options)) {
            return $choice;
        }

        // Handle invalid input
        $this->error("❌ Invalid selection: {$choice}");
        $this->newLine();
        return $this->selectMcpServer(); // Recursive call for retry
    }

    protected function continueWithInitialization(string $serverName): void
    {
        $this->newLine();
        $this->comment('🔧 INITIALIZATION PHASE');
        $this->line('════════════════════════');

        // Get server configuration
        $serverConfig = config("mcp-servers.{$serverName}");
        $this->displayServerConfig($serverName, $serverConfig);

        $this->newLine();
        $this->info('💡 Establishing MCP connection...');

        try {
            $session = MCP::connect($serverName);

            $this->info('✅ Connection established successfully!');
            $this->displayConnectionStatus($session);

            // Check available capabilities
            $hasAnyCapabilities = $session->has_tools || $session->has_resources || $session->has_prompts;

            if (!$hasAnyCapabilities) {
                $this->displayNoCapabilities();
                return;
            }

            // Display capabilities menu and get user choice
            $selectedCapability = $this->selectCapability($session);

            if ($selectedCapability === 'back') {
                $this->info('🔙 Going back to server selection...');
                $this->newLine();
                $this->handle(); // Restart the command
            }

            $this->info("🎯 Selected capability: <fg=cyan>{$selectedCapability}</>");
            $this->continueWithCapabilityExploration($selectedCapability, $session);

        } catch (\Exception $e) {
            $this->error("❌ Failed to connect to MCP server: {$e->getMessage()}");
            $this->newLine();

            if ($this->confirm('Would you like to try again?', true)) {
                $this->continueWithInitialization($serverName);
            }
        }
    }

    protected function displayConnectionStatus($session): void
    {
        $this->newLine();
        $this->comment('📊 CONNECTION STATUS');
        $this->line('═══════════════════');

        $this->line("  <fg=yellow>Session ID:</> {$session->session_id}");
        $this->line("  <fg=yellow>Has Tools:</> " . ($session->has_tools ? '<fg=green>✓ Yes</>' : '<fg=red>✗ No</>'));
        $this->line("  <fg=yellow>Has Resources:</> " . ($session->has_resources ? '<fg=green>✓ Yes</>' : '<fg=red>✗ No</>'));
        $this->line("  <fg=yellow>Has Prompts:</> " . ($session->has_prompts ? '<fg=green>✓ Yes</>' : '<fg=red>✗ No</>'));
        $this->line("  <fg=yellow>Messages Sent:</> " . count($session->messages_sent));
        $this->line("  <fg=yellow>Messages Received:</> " . count($session->messages_received));
    }

    protected function displayNoCapabilities(): void
    {
        $this->newLine();
        $this->warn('⚠️  NO CAPABILITIES DETECTED');
        $this->line('═══════════════════════════════');
        $this->error('This MCP server does not expose any capabilities (tools, resources, or prompts).');
        $this->line('This could mean:');
        $this->line('  • The server is still starting up');
        $this->line('  • The server has no capabilities configured');
        $this->line('  • There was an issue during the handshake');
        $this->newLine();

        if ($this->confirm('Would you like to go back and select a different server?', true)) {
            $this->newLine();
            $this->handle(); // Restart the command
        }

        $this->info('👋 Goodbye!');
    }

    protected function selectCapability($session): string
    {
        $this->newLine();
        $this->info('🎯 Available Capabilities:');
        $this->newLine();

        // Build capability options
        $options = [];
        $displayOptions = [];

        if ($session->has_tools) {
            $options[] = 'tools';
            $displayOptions[] = '<fg=green>🔧 Tools</> - Execute server-provided functions';
        }

        if ($session->has_resources) {
            $options[] = 'resources';
            $displayOptions[] = '<fg=blue>📁 Resources</> - Browse server-provided data sources';
        }

        if ($session->has_prompts) {
            $options[] = 'prompts';
            $displayOptions[] = '<fg=magenta>💬 Prompts</> - Use server-provided prompt templates';
        }

        // Add back option
        $options[] = 'back';
        $displayOptions[] = '<fg=red>🔙 Back to server selection</>';

        // Display numbered menu
        foreach ($displayOptions as $index => $display) {
            $this->line(sprintf('  <fg=cyan>%d.</> %s', $index + 1, $display));
        }

        $this->newLine();

        // Get user choice
        $choice = $this->ask('Select a capability to explore (enter number or name)', '1');

        // Handle numeric input
        if (is_numeric($choice)) {
            $index = (int)$choice - 1;
            if (isset($options[$index])) {
                return $options[$index];
            }
        }

        // Handle string input
        if (in_array($choice, $options)) {
            return $choice;
        }

        // Handle invalid input
        $this->error("❌ Invalid selection: {$choice}");
        $this->newLine();
        return $this->selectCapability($session); // Recursive call for retry
    }

    protected function continueWithCapabilityExploration(string $capability, ClientSession $session): void
    {
        $this->newLine();
        $this->comment("🔍 EXPLORING {$capability}");
        $this->line('═══════════════════════════════');

        // Based on the selected capability, implement the appropriate exploration interface:
        switch ($capability) {
            case 'tools':
                $this->exploreTools($session);
                break;

            case 'resources':
                $this->warn('⚠️  Resources exploration implementation continues here...');
                // Next steps:
                // 1. List available resources from $session->resources
                // 2. Display resource URIs and metadata
                // 3. Allow user to browse/read resources
                // 4. Show resource content
                break;

            case 'prompts':
                $this->warn('⚠️  Prompts exploration implementation continues here...');
                // Next steps:
                // 1. List available prompts from $session->prompts
                // 2. Display prompt templates and arguments
                // 3. Allow user to execute prompts with parameters
                // 4. Show prompt results
                break;
        }

        $this->newLine();
        $this->info('💡 Implementation ready for next phase...');
    }

    protected function exploreTools(ClientSession $session): void
    {
        $session = $session->initListTools();
        
        while (true) {
            $this->newLine();
            $this->info('🔧 Available Tools:');
            $this->newLine();
            
            if (empty($session->tools)) {
                $this->warn('❌ No tools available from this server.');
                return;
            }
            
            // Display tools menu
            $toolOptions = [];
            $displayOptions = [];
            
            foreach ($session->tools as $index => $tool) {
                $toolOptions[] = $tool['name'];
                $displayOptions[] = sprintf(
                    "%d. <fg=cyan>%s</> - %s",
                    $index + 1,
                    $tool['title'] ?? $tool['name'],
                    $this->truncateDescription($tool['description'] ?? 'No description available')
                );
            }
            
            // Add back option
            $toolOptions[] = 'back';
            $displayOptions[] = 'back. 🔙 <fg=gray>Go back to capability selection</>';
            
            foreach ($displayOptions as $option) {
                $this->line($option);
            }
            
            $this->newLine();
            $choice = $this->ask('Select a tool to use (number, name, or "back")', '1');
            
            // Parse choice
            $selectedTool = null;
            if (is_numeric($choice)) {
                $index = (int)$choice - 1;
                if (isset($toolOptions[$index])) {
                    $selectedTool = $toolOptions[$index];
                } else {
                    $this->error("❌ Invalid selection: {$choice}");
                    continue; // Continue the loop instead of returning
                }
            } else {
                // Handle string input
                if (in_array($choice, $toolOptions)) {
                    $selectedTool = $choice;
                } else {
                    $this->error("❌ Invalid selection: {$choice}");
                    continue; // Continue the loop instead of returning
                }
            }
            
            // Handle back selection
            if ($selectedTool === 'back') {
                $this->info('🔙 Going back to capability selection...');
                $this->newLine();
                return; // Exit the loop and method
            }
            
            $this->info("🎯 Selected tool: <fg=cyan>{$selectedTool}</>");
            $this->newLine();
            
            // Find the selected tool details
            $toolDetails = null;
            foreach ($session->tools as $tool) {
                if ($tool['name'] === $selectedTool) {
                    $toolDetails = $tool;
                    break;
                }
            }
            
            if (!$toolDetails) {
                $this->error("❌ Tool details not found for: {$selectedTool}");
                continue; // Continue the loop instead of returning
            }
            
            // TODO: Cursor Agent - Create interactive parameter injection here.
            $this->collectToolParameters($toolDetails, $session);
            
            // After tool execution, the loop will continue and show the tools menu again
        }
    }

    protected function collectToolParameters(array $toolDetails, ClientSession $session): void
    {
        $parameters = [];
        $inputSchema = $toolDetails['inputSchema'] ?? [];
        $properties = $inputSchema['properties'] ?? [];
        $required = $inputSchema['required'] ?? [];

        if (empty($properties)) {
            $this->info('🎯 This tool requires no parameters. Executing...');
            $this->executeToolWithParameters($toolDetails, $parameters, $session);
            return;
        }

        $this->comment('📝 PARAMETER COLLECTION');
        $this->line('═══════════════════════');
        $this->info('Please provide the following parameters:');
        $this->newLine();

        // First, collect all required parameters
        $hasRequired = false;
        foreach ($properties as $paramName => $paramDetails) {
            if (in_array($paramName, $required)) {
                $hasRequired = true;
                $this->line("🔸 <fg=red>Required:</> <fg=yellow>{$paramName}</> ({$paramDetails['type']})");
                $this->line("   {$paramDetails['description']}");

                $value = $this->collectParameterValue($paramName, $paramDetails, true);
                if ($value !== null) {
                    $parameters[$paramName] = $value;
                }
                $this->newLine();
            }
        }

        // Then, offer optional parameters
        $hasOptional = false;
        foreach ($properties as $paramName => $paramDetails) {
            if (!in_array($paramName, $required)) {
                $hasOptional = true;
                break;
            }
        }

        if ($hasOptional) {
            $this->info('🔹 Optional Parameters:');
            $includeOptional = $this->confirm('Would you like to provide any optional parameters?', false);

            if ($includeOptional) {
                foreach ($properties as $paramName => $paramDetails) {
                    if (!in_array($paramName, $required)) {
                        $this->line("🔹 <fg=gray>Optional:</> <fg=yellow>{$paramName}</> ({$paramDetails['type']})");
                        $this->line("   {$paramDetails['description']}");

                        $includeThis = $this->confirm("Include {$paramName}?", false);
                        if ($includeThis) {
                            $value = $this->collectParameterValue($paramName, $paramDetails, false);
                            if ($value !== null) {
                                $parameters[$paramName] = $value;
                            }
                        }
                        $this->newLine();
                    }
                }
            }
        }

        $this->executeToolWithParameters($toolDetails, $parameters, $session);
    }

    protected function collectParameterValue(string $paramName, array $paramDetails, bool $required): mixed
    {
        $type = $paramDetails['type'] ?? 'string';
        $default = $paramDetails['default'] ?? null;

        switch ($type) {
            case 'string':
                $prompt = "Enter {$paramName}";
                if ($default !== null) {
                    $prompt .= " [default: {$default}]";
                }
                $value = $this->ask($prompt, $default);
                return empty($value) && !$required ? null : $value;

            case 'object':
                $this->line("💡 For object parameters, provide JSON format:");
                $prompt = "Enter {$paramName} (JSON object)";
                if ($default !== null) {
                    $defaultJson = is_string($default) ? $default : json_encode($default);
                    $prompt .= " [default: {$defaultJson}]";
                }

                $input = $this->ask($prompt, $default ? json_encode($default) : '{}');
                if (empty($input) && !$required) {
                    return null;
                }

                try {
                    return json_decode($input, true, 512, JSON_THROW_ON_ERROR);
                } catch (\JsonException $e) {
                    $this->error("Invalid JSON format. Please try again.");
                    return $this->collectParameterValue($paramName, $paramDetails, $required);
                }

            case 'integer':
            case 'number':
                $prompt = "Enter {$paramName} (number)";
                if ($default !== null) {
                    $prompt .= " [default: {$default}]";
                }
                $value = $this->ask($prompt, $default);
                if (empty($value) && !$required) {
                    return null;
                }
                return $type === 'integer' ? (int)$value : (float)$value;

            case 'boolean':
                $prompt = "Enter {$paramName} (true/false)";
                return $this->confirm($prompt, $default ?? false);

            case 'array':
                $this->line("💡 For array parameters, provide comma-separated values:");
                $prompt = "Enter {$paramName} (comma-separated)";
                if ($default !== null) {
                    $defaultStr = is_array($default) ? implode(',', $default) : $default;
                    $prompt .= " [default: {$defaultStr}]";
                }

                $input = $this->ask($prompt, $default ? implode(',', (array)$default) : '');
                if (empty($input) && !$required) {
                    return null;
                }
                return array_map('trim', explode(',', $input));

            default:
                $prompt = "Enter {$paramName}";
                if ($default !== null) {
                    $prompt .= " [default: {$default}]";
                }
                $value = $this->ask($prompt, $default);
                return empty($value) && !$required ? null : $value;
        }
    }

    protected function executeToolWithParameters(array $toolDetails, array $parameters, ClientSession $session): void
    {
        $this->newLine();
        $this->comment('🎯 TOOL EXECUTION');
        $this->line('═══════════════════');

        $title = $toolDetails['title'] ?? $toolDetails['name'];
        $this->info("Tool: {$title}");
        $this->info('Parameters:');
        if (!empty($parameters)) {
            foreach ($parameters as $key => $value) {
                $displayValue = is_array($value) || is_object($value) ? json_encode($value) : $value;
                $this->line("  • <fg=yellow>{$key}:</> {$displayValue}");
            }
        } else {
            $this->line('  • <fg=gray>None</> ');
        }
        $this->newLine();


        $session = $session->use_tool($toolDetails['name'], $parameters);
        $result = $session->tool_response();
        
        $this->displayToolResult($result, $toolDetails);
        
        // Press any key to continue
        $this->newLine();
        $this->info('Press any key to continue...');
        $this->ask('');
    }

    protected function displayToolResult(array $result, array $toolDetails): void
    {
        $this->newLine();
        $this->comment('📋 TOOL EXECUTION RESULT');
        $this->line('════════════════════════════');
        
        // Check if there's an error in the JSON-RPC response
        if (isset($result['error'])) {
            $this->error('❌ Tool execution failed:');
            $this->line("Error Code: {$result['error']['code']}");
            $this->line("Error Message: {$result['error']['message']}");
            if (isset($result['error']['data'])) {
                $this->line("Error Details: {$result['error']['data']}");
            }
            return;
        }
        
        // Extract the actual content
        $toolResult = $result['result'] ?? null;
        if (!$toolResult) {
            $this->warn('⚠️  No result data received from tool');
            return;
        }
        
        if ($toolResult['isError'] ?? false) {
            $this->error('❌ Tool reported an error during execution');
            return;
        }
        
        $content = $toolResult['content'][0]['text'] ?? null;
        if (!$content) {
            $this->warn('⚠️  No content received from tool');
            return;
        }
        
        $this->info("✅ Tool executed successfully:");
        $this->newLine();
        
        // Try to parse as JSON for pretty display
        $jsonData = json_decode($content, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            // Content is valid JSON - display it formatted
            if (is_array($jsonData)) {
                $this->displayJsonResult($jsonData);
            } else {
                $this->line('<fg=green>Result:</> ' . json_encode($jsonData, JSON_PRETTY_PRINT));
            }
        } else {
            // Content is plain text
            $this->line('<fg=green>Result:</>');
            $this->line($content);
        }
    }
    
    protected function displayJsonResult(array $data): void
    {
        if (empty($data)) {
            $this->line('<fg=yellow>Empty result set</>');
            return;
        }
        
        // Handle different JSON result types
        if (isset($data[0]) && is_array($data[0])) {
            // Array of objects (like query results)
            $this->line('<fg=green>Query Results:</> ' . count($data) . ' row(s)');
            $this->newLine();
            
            foreach ($data as $index => $row) {
                $this->line("<fg=cyan>Row " . ($index + 1) . ":</>");
                foreach ($row as $key => $value) {
                    $displayValue = is_array($value) || is_object($value) ? json_encode($value) : $value;
                    $this->line("  • <fg=yellow>{$key}:</> {$displayValue}");
                }
                if ($index < count($data) - 1) {
                    $this->newLine();
                }
            }
        } else {
            // Single object or other structure
            $this->line('<fg=green>Result Data:</>');
            $this->line(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        }
    }

    protected function displayServerConfig(string $serverName, array $config): void
    {
        $this->info("📋 Server Configuration for <fg=cyan>{$serverName}</>");

        foreach ($config as $key => $value) {
            if ($key === 'env' && is_array($value)) {
                $this->line("  <fg=yellow>{$key}:</> [" . count($value) . " environment variables]");
                foreach ($value as $envKey => $envValue) {
                    // Mask sensitive values
                    $displayValue = $this->maskSensitiveValue($envKey, $envValue);
                    $this->line("    <fg=blue>{$envKey}:</> {$displayValue}");
                }
            } elseif ($key === 'args' && is_array($value)) {
                $this->line("  <fg=yellow>{$key}:</> " . implode(' ', $value));
            } elseif ($key === 'headers' && is_array($value)) {
                $this->line("  <fg=yellow>{$key}:</> [" . count($value) . " headers]");
                foreach ($value as $headerKey => $headerValue) {
                    $displayValue = $this->maskSensitiveValue($headerKey, $headerValue);
                    $this->line("    <fg=blue>{$headerKey}:</> {$displayValue}");
                }
            } else {
                $displayValue = $this->maskSensitiveValue($key, $value);
                $this->line("  <fg=yellow>{$key}:</> {$displayValue}");
            }
        }
    }

    protected function maskSensitiveValue(string $key, mixed $value): string
    {
        $sensitiveKeys = ['password', 'token', 'key', 'secret', 'auth'];

        foreach ($sensitiveKeys as $sensitiveKey) {
            if (stripos($key, $sensitiveKey) !== false) {
                return str_repeat('*', min(8, strlen((string)$value)));
            }
        }

        return (string)$value;
    }

    protected function truncateString(string $str, int $length): string
    {
        return strlen($str) > $length ? substr($str, 0, $length - 3) . '...' : $str;
    }

    protected function truncateDescription(string $description): string
    {
        return strlen($description) > 80 ? substr($description, 0, 77) . '...' : $description;
    }
}
