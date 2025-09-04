<?php

if(!function_exists('supported_protocol_versions')) {
    /**
     * Get the supported protocol versions.
     *
     * @return array
     */
    function supported_protocol_versions(): array
    {
        return config('superconductor.protocol_versions.supported', []);
    }
}

if(!function_exists('default_protocol_version')) {
    /**
     * Get the default protocol version.
     *
     * @return string
     */
    function default_protocol_version(): string
    {
        return config('superconductor.protocol_versions.default', '2024-11-05');
    }
}
