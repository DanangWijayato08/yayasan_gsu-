<?php

namespace App\Services;

use Illuminate\Support\Str;

class InputSanitizer
{
    /**
     * Sanitize user input to prevent XSS attacks
     */
    public static function sanitize(string $input, bool $allowHtml = false): string
    {
        // Trim whitespace
        $input = trim($input);

        if (!$allowHtml) {
            // Remove all HTML tags
            $input = strip_tags($input);
        } else {
            // For rich text, use a whitelist approach
            $input = self::sanitizeHtml($input);
        }

        // Decode HTML entities
        $input = html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove any null bytes
        $input = str_replace("\0", '', $input);

        // Remove control characters except newline and carriage return
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $input);

        return $input;
    }

    /**
     * Sanitize HTML content using whitelist approach
     */
    private static function sanitizeHtml(string $html): string
    {
        // Allowed HTML tags for rich text editors
        $allowedTags = '<p><br><strong><b><em><i><u><h1><h2><h3><h4><h5><h6><ul><ol><li><blockquote><a><img>';

        $html = strip_tags($html, $allowedTags);

        // Remove dangerous attributes
        $html = preg_replace('/\s+on\w+\s*=/i', '', $html);
        $html = preg_replace('/javascript:/i', '', $html);
        $html = preg_replace('/data:/i', '', $html);

        return $html;
    }

    /**
     * Sanitize array values recursively
     */
    public static function sanitizeArray(array $data, bool $allowHtml = false): array
    {
        return array_map(function ($value) use ($allowHtml) {
            if (is_array($value)) {
                return self::sanitizeArray($value, $allowHtml);
            }

            if (is_string($value)) {
                return self::sanitize($value, $allowHtml);
            }

            return $value;
        }, $data);
    }

    /**
     * Sanitize URL to prevent SSRF and open redirect attacks
     */
    public static function sanitizeUrl(string $url): ?string
    {
        $url = trim($url);

        if (empty($url)) {
            return null;
        }

        // Validate URL format
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parsed = parse_url($url);

        // Only allow http and https schemes
        if (!isset($parsed['scheme']) || !in_array($parsed['scheme'], ['http', 'https'])) {
            return null;
        }

        // Prevent localhost/internal network access (SSRF protection)
        $host = $parsed['host'] ?? '';
        
        // Block private IP ranges and localhost
        $blockedHosts = ['localhost', '127.0.0.1', '0.0.0.0', '::1'];
        if (in_array($host, $blockedHosts)) {
            return null;
        }

        // Check for private IP ranges
        if (ip2long($host) !== false) {
            $ip = ip2long($host);
            if (
                ($ip >= ip2long('10.0.0.0') && $ip <= ip2long('10.255.255.255')) ||
                ($ip >= ip2long('172.16.0.0') && $ip <= ip2long('172.31.255.255')) ||
                ($ip >= ip2long('192.168.0.0') && $ip <= ip2long('192.168.255.255'))
            ) {
                return null;
            }
        }

        return $url;
    }
}
