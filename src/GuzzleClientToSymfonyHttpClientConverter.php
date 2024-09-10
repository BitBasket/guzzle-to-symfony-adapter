<?php declare(strict_types=1);

/**
 * This file is part of Guzzle To Symfony HTTP Client Adapter, a project of BitBasket, FZC.
 *
 * Copyright © 2024 BitBasket, FZC (SPCFZ, UAE).
 *   https://www.bitbasket.co/
 *   https://github.com/BitBasket/guzzle-to-symfony-adapter
 *
 * This file is licensed under the MIT License.
 */

namespace BitBasket\GuzzleToSymfonyAdapter;

class GuzzleClientToSymfonyHttpClientConverter
{
    public static function convertFromGuzzle5ToSymfonyHttpClient(GuzzleHttp\Client $guzzleClient): Symfony\Contracts\HttpClient\HttpClientInterface
    {
        // Extract common configuration from Guzzle v5 client
        // Use reflection to access the protected method `getDefaultOptions()`
        $reflection = new ReflectionMethod($guzzleClient, 'getDefaultOptions');
        $reflection->setAccessible(true);
        $guzzleOptions = $reflection->invoke($guzzleClient);

        // Mapping Guzzle configuration to Symfony HttpClient configuration
        $symfonyConfig = [];

        // Handle base URL
        if (isset($guzzleOptions['base_url'])) {
            $symfonyConfig['base_uri'] = $guzzleOptions['base_url'];
        }

        // Handle default headers
        if (isset($guzzleOptions['defaults']['headers'])) {
            $symfonyConfig['headers'] = $guzzleOptions['defaults']['headers'];
        }

        // Handle timeout
        if (isset($guzzleOptions['defaults']['timeout'])) {
            $symfonyConfig['timeout'] = $guzzleOptions['defaults']['timeout'];
        }

        // Handle HTTP version
        if (isset($guzzleOptions['defaults']['version'])) {
            $symfonyConfig['http_version'] = $guzzleOptions['defaults']['version'];
        }

        // Handle verify (SSL verification)
        if (isset($guzzleOptions['defaults']['verify'])) {
            $symfonyConfig['verify_peer'] = $guzzleOptions['defaults']['verify'];
        }

        // Convert Guzzle client to Symfony HttpClient
        return HttpClient::create($symfonyConfig);
    }

    public static function convertFromGuzzle6Or7ToSymfonyHttpClient(GuzzleHttp\Client $guzzleClient): Symfony\Contracts\HttpClient\HttpClientInterface
    {
        $guzzleConfig   = $guzzleClient->getConfig();
        $symfonyOptions = [];

        // Base URI
        if (isset($guzzleConfig['base_url'])) {
            $symfonyOptions['base_uri'] = $guzzleConfig['base_url'];
        }

        // Default headers
        if (isset($guzzleConfig['defaults']['headers'])) {
            $symfonyOptions['headers'] = $guzzleConfig['defaults']['headers'];
        }

        // Timeout
        if (isset($guzzleConfig['defaults']['timeout'])) {
            $symfonyOptions['timeout'] = $guzzleConfig['defaults']['timeout'];
        }

        // Connect timeout
        if (isset($guzzleConfig['defaults']['connect_timeout'])) {
            $symfonyOptions['max_duration'] = $guzzleConfig['defaults']['connect_timeout'];
        }

        // Verify SSL
        if (isset($guzzleConfig['defaults']['verify'])) {
            $symfonyOptions['verify_peer'] = $guzzleConfig['defaults']['verify'];
            $symfonyOptions['verify_host'] = $guzzleConfig['defaults']['verify'];
        }

        // SSL key and cert
        if (isset($guzzleConfig['defaults']['ssl_key'])) {
            $symfonyOptions['local_cert'] = $guzzleConfig['defaults']['ssl_key'];
        }
        if (isset($guzzleConfig['defaults']['cert'])) {
            $symfonyOptions['local_pk'] = $guzzleConfig['defaults']['cert'];
        }

        // Proxy
        if (isset($guzzleConfig['defaults']['proxy'])) {
            $symfonyOptions['proxy'] = $guzzleConfig['defaults']['proxy'];
        }

        // Auth
        if (isset($guzzleConfig['defaults']['auth'])) {
            $auth = $guzzleConfig['defaults']['auth'];
            if (is_array($auth) && count($auth) >= 2) {
                $symfonyOptions['auth_basic'] = $auth[0] . ':' . $auth[1];
            }
        }

        // User agent
        if (isset($guzzleConfig['defaults']['headers']['User-Agent'])) {
            $symfonyOptions['headers']['User-Agent'] = $guzzleConfig['defaults']['headers']['User-Agent'];
        }

        // Create and return the Symfony HttpClient
        return HttpClient::create($symfonyOptions);
    }
}
