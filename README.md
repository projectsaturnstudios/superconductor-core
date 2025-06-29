20241105


* JSON-RPC responses
* Stateful Sessions
* Capability negotiation

Server Features
* Resources
* Prompts
* Tools

Client Features
* Sampling
* Roots
* Elicitation

Utilities
* Configuration
* Progress Tracking
* Cancellation
* Error Reporting
* Logging

Security
* User Consent and Control
* Data Privacy
* Tool Safety
* LLM Sampling Controls
* Completely optional


Servers
* Should be easy to build
* Should  be highly composable
* Should not be able to read the whole conversation, nor "see into" other servers
* Features can be added to servers and clients progressively

Capability Negotiation
* Servers declare capabilities like resource subscriptions, tool support and prompt templates
* Clients declare capabilities like sampling support and notification handling
* Both parties must respect declared capabilities throughout the session
* Additional capabilities can be negotiated through extensions to the protocol

Additionally, for example
* Implemented server features must be advertised in the server's capabilities
* Emitting resource subscription notifications requires the server to declare subscription support
* Tool invocation requires the server to declare tool capabilities
* Sampling requires the client to declare support in its capabilities

Protocol Layers
* Base Protocol - JSON-RPC message types                                                        ** REQUIRED ** This package will use projectsaturnstudios/json-rpc for RPC Method dispatching and message handling.
* Lifecycle Management - Connection initialization, capability negotiation, and session control ** REQUIRED **
* Server Features - Resources, Prompts and Tools exposed by servers                             ** OPTIONAL **
* Client Features - Sampling and Root Directory lists provided by clients                       ** OPTIONAL **
* Utilities - Cross-cutting concerns like logging and argument completion                       ** OPTIONAL **

Lifecycle - 
* Initialization - Capability negotiation and protocol version agreement
* Operation - Normal protocol communication
* Shutdown - Graceful termination of the connection

Initialization Phase
1. Client sends initialize method request.
2. Server responds with initialize method response.
3. Client sends initialized notification which warrants no response.

Operation Phase
1. Normal Operations

Shutdown
1. Client Disconnects from server.

Connection Closed
1. mkay.

Initialization Phase
Initialization must be the first interaction between client and server.
Client and Server:
* Establish protocol version compatibility
* Exchange and negotiate capabilities
* Share implementation details

Client initiates the connection by sending an `initialize` method request containing:
1. Protocol version supported by the client
2. Client capabilities (e.g., sampling support, notification handling)
3. Client implementation details (e.g., client name, version)
Could look something like this:
```json
{
    "jsonrpc": "2.0",
    "id": 1,
    "method": "initialize",
    "params": {
        "protocolVersion": "2024-11-05",
        "capabilities": {
            "roots": {
                "listChanged": true
            },
            "sampling": {}
        },
        "clientInfo": {
            "name": "ExampleClient",
            "version": "1.0.0"
        }
    }
}
```
When the server response to this request it should look like this:
```json
{
    "jsonrpc": "2.0",
    "id": 1,
    "result": {
        "protocolVersion": "2024-11-05",
        "capabilities": {
            "logging": {},
            "prompts": {
                "listChanged": true
            },
            "resources": {
                "subscribe": true,
                "listChanged": true
            },
            "tools": {
                "listChanged": true
            }
        },
        "serverInfo": {
            "name": "ExampleServer",
            "version": "1.0.0"
        }
    }
}
```

The client then sends an `initialized` notification to indicate that it is ready to proceed with the session. This notification does not require a response from the server.
```json
{
    "jsonrpc": "2.0",
    "method": "notifications/initialized"
}
```

Capabilities themselves - specifically Prompts resources are tools can  have their own sub-capabilities.
listChanged - support for list change notifications 
subscribe - support for resource subscription notifications

Operation Phase
During the operation phase, the client and server exchange messages according to the negotiated capabilities.

Both parties SHOULD:

* Respect the negotiated protocol version
* Only use capabilities that were successfully negotiated

This means that if the client calls a tool that I have, but it was not in the list of capabilities, the server should respond with an error.
This isn't any different than calling a route that's not exposed normally in http-land


Shutdown
During the shutdown phase, one side (usually the client) cleanly terminates the protocol connection. 
No specific shutdown messages are defined—instead, the underlying transport mechanism should be used 
to signal connection termination:

stdio
For the stdio transport, the client SHOULD initiate shutdown by:

First, closing the input stream to the child process (the server)
Waiting for the server to exit, or sending SIGTERM if the server does not exit within a reasonable time
Sending SIGKILL if the server does not exit within a reasonable time after SIGTERM
The server MAY initiate shutdown by closing its output stream to the client and exiting.

HTTP
For HTTP transports, shutdown is indicated by closing the associated HTTP connection(s).

Error Handling
Implementations SHOULD be prepared to handle these error cases:

Protocol version mismatch
Failure to negotiate required capabilities
Initialize request timeout
Shutdown timeout

Example Error Response
```json
{
    "jsonrpc": "2.0",
    "id": 1,
    "error": {
        "code": -32602,
        "message": "Unsupported protocol version",
        "data": {
            "supported": ["2024-11-05"],
            "requested": "1.0.0"
        }
    }
}
```


Transports

stdio
HTTP with Server-Sent Events (SSE)
Custom Transports (e.g., WebSocket, gRPC)


stdio lifecycle
* Client starts the server process (php artisan mcp:start)
* The server receives JSON-RPC messages from the client via stdin
* The server sends JSON-RPC messages to the client via stdout
* Messages are delimited by newlines, and MUST NOT contain embedded newlines.
* The server MAY write UTF-8 strings to its standard error (stderr) for logging purposes. Clients MAY capture, forward, or ignore this logging.
* The server MUST NOT write anything to its stdout that is not a valid MCP message.
* The client MUST NOT write anything to the server’s stdin that is not a valid MCP message.

* Client launches the server process
Loop
* Client writes to server's stdin
* Server writes to its stdout
* Server writes to its stderr for logging
EndLoop
* Client closes the server process

Http with SSE lifecycle
Security Warning
When implementing HTTP with SSE transport:

Servers MUST validate the Origin header on all incoming connections to prevent DNS rebinding attacks
When running locally, servers SHOULD bind only to localhost (127.0.0.1) rather than all network interfaces (0.0.0.0)
Servers SHOULD implement proper authentication for all connections
Without these protections, attackers could use DNS rebinding to interact with local MCP servers from remote websites.

The server MUST provide two endpoints:

An SSE endpoint, for clients to establish a connection and receive messages from the server
A regular HTTP POST endpoint for clients to send messages to the server
When a client connects, the server MUST send an endpoint event containing a URI for the client to use for sending messages. All subsequent client messages MUST be sent as HTTP POST requests to this endpoint.

Server messages are sent as SSE message events, with the message content encoded as JSON in the event data.

* Client send HTTP POST request to server's SSE endpoint
* Server response with an SSE stream Content-Type: text/event-stream
* Server then sends an endpoint event to the client
Loop
* Client sends HTTP POST request to server's endpoint
* Server accepts the message and sends a 202 Accepted response
* Server asynchronously processes the message and sends a response to the client via still open SSE stream
EndLoop
* Client closes the SSE connection


SSE Steps
1. 
