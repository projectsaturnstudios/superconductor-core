![Superconductor](superconductor.jpg)

# Superconductor Core

[![Latest Version on Packagist](https://img.shields.io/packagist/v/projectsaturnstudios/superconductor-core.svg?style=flat-square)](https://packagist.org/packages/projectsaturnstudios/superconductor-core)
[![Total Downloads](https://img.shields.io/packagist/dt/projectsaturnstudios/superconductor-core.svg?style=flat-square)](https://packagist.org/packages/projectsaturnstudios/superconductor-core)

MCP - Decoupled, elegant, use capabilities directly, registered like routes. Use any capabilities you want, or dont. 
Use MCP/RPC Methods for building Servers and Clients. All the MCP methods are here, also registered like routes!
Add transport layers to export your Server the way you want, or not at all. Your Clients can support any transport layer you want, or not at all.

MCP for Artisans

- **Decoupled Architecture**: Use Capabilities directly, or as an MCP Server or Client. Work with the transports you want. Or not at all.

- **Feels like Laravel**: Adding this package and building it with modules, makes MCP feel like Laravel 

- **Only the capabilities you want**: You can use only a tools module, and not have resources code floating around. Bonus - they register like routes!

- **Server, Client, or something else**: Use the RPC Layer to manage your capabilities with MCP-supported method calls. Or add your own. Register them like routes, too!!
 
- **Transport-agnostic**: Expose your MCP-enabled Applications the way you want. STDIO, Http over SSE, StreamableHttp, or roll your own transport layer. Plug it in, ask your agent to tap in.

- **Local Agents with direct-access**: Build all the agents for your application, and execute your tools without needing to build an MCP Client or a Server. Then test them with your MCP Client!!

Get started with PocketFlow PHP:
- **Installation**: `composer require projectsaturnstudios/superconductor-core:^20241105.1.0`
- **Tools**: `composer require superconductor/tools:^20241105.1.0`
- **Resources**: `composer require superconductor/resources:^20241105.1.0`
- **Prompts**: `composer require superconductor/prompts:^20241105.1.0`
- **Sampling**: `composer require superconductor/sampling:^20241105.1.0`
- **Roots**: `composer require superconductor/roots:^20241105.1.0`
- **Experimental Elicitation**: `composer require superconductor/elicitation:^20241105.1.0`


To Use:
```php

use MCP\Support\Facades\MCP;
use MCP\Capabilities\CapabilityExecution\CapabilityResult;
use MCP\Capabilities\CapabilityExecution\CapabilityRequest;

class SomeClass
{
    public function handle(): CapabilityResult
    {
        $request = new CapabilityRequest($name, [...$params]);
        return MCP::action(
            $client_or_server, // Use 'client' or 'server' depending on direction
            $capability,    // eg, 'tools', 'resources', 'prompts', 'sampling', 'roots', '<experimental_capability>'
            $action_skill,  // The name of capability action/skill the request wants to use 
            $request,  // The request from above 
            $experimental_if_not_official // Optional, if the capability is experimental ie, not a tool, resource, prompt, sampling, or root as of 2024-11-05
        );
    }
}
```

Register experimental capabilities.
Super conductor only has built in support for experiemental capabilities.
You can extend them with official MCP-support capabilities like tools, resources, prompts, sampling, and roots.
To make experimental capabilities, register them in routes.mcp.php like this:

```php
use MCP\Support\Facades\MCP;

MCP::experimental('client', 'elicitation', SomeClass::class)->name('Elicitation Capability');
MCP::experimental('server', 'something-wild', SomeClass::class)->name('Wild Capability');

````

Call them like the mentioned above previously, and you have working experimental capabilities.

Example capability registration with Superconductor Capability Packages
```php
use MCP\Support\Facades\MCP;
// When superconductor/tools is  installed, you can register tools in routes/tools.php like this:
MCP::tool('echo', SomeClass::class)->name('Echo Tool');

// When superconductor/resources is  installed, you can register resources in routes/resources.php like this:
MCP::resource('mcp://protocol.txt', SomeClass::class)->name('MCP Protocol Resource');

// When superconductor/prompts is  installed, you can register Prompts  in routes/prompts.php like this:
MCP::prompt('analyze-data', SomeClass::class)->name('Analyze Data Prompt');

// When superconductor/sampling is  installed, you can register Samples  in routes/sampling.php like this:
MCP::sample('ask-client', SomeClass::class)->name('Ask Client Question');

// When superconductor/roots is  installed, you can register Roots  in routes/roots.php like this:
MCP::root('client://segmented-information-repo-a', SomeClass::class)->name('Repo A Root');
```    
