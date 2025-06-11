![Superconductor](superconductor.jpg)

# Superconductor Core

[![Latest Version on Packagist](https://img.shields.io/packagist/v/projectsaturnstudios/superconductor-core.svg?style=flat-square)](https://packagist.org/packages/projectsaturnstudios/superconductor-core)
[![Total Downloads](https://img.shields.io/packagist/dt/projectsaturnstudios/superconductor-core.svg?style=flat-square)](https://packagist.org/packages/projectsaturnstudios/superconductor-core)

The power of the Model Context Protocol in your Laravel apps!

## Table of Contents

<details><summary>Click to expand</summary><p>

- [Introduction](#introduction)

</p></details>

## Introduction

Superconductor is a novel, elegant, transport-layer-agnostic microframework for implementing the Model Context Protocol 
(MCP) within your laravel applications! Setup only takes a few steps -
1. Install the package
2. Install a supported transport package and now you have an MCP Server

Couple this package with a compatible Agent-generator and you have a tool-management setup for Agents!
The modular nature of this package enables developer to support any transport protocols they wish and none that they 
don't!


# Tools
Tool usage is easy to manager with superconductor. 
The default management driver is the config driver, meaning you simply
register your tools in the mcp config - in 
features -> capabilities -> registrar - available ->hardcoded -> tools.

Tools can be dispatched without a Session being active, simply supply a 
ToolCallRequest of any compatible MCP version and dispatch with
```php
MCPCapabilities::dispatch('tools', $incoming_message);
```

This system-agnostic dispatching mechanism means, not only can agents connected
via MCP can use the tools, they can be provided to Agent dispatched directly as well.

To create a tool run: 
```shell
php artisan make:tool <name>
```
