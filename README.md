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

```php

$client = MyMCPClient::initialize($mcp_server);

$tools_available_on_server = $client->listTools();
$results = $client->callTool($tool_name)

```
