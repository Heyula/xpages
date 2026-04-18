<?php

declare(strict_types=1);

/**
 * xPages — URL and filename sanitisation helpers.
 *
 * Both helpers are defence-in-depth: they accept loose input (strings,
 * possibly containing control characters, entity-encoded payloads, or
 * traversal attempts) and return a safe normalised value or an empty
 * string.
 *
 * @package xpages
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * URL değerini doğrula ve normalize et.
 *
 * İzin verilenler: http, https, ftp, mailto ve göreli URL'ler.
 * Returns empty string on anything invalid — callers MUST treat empty
 * as "reject", not "leave unchanged".
 */
function xpages_normalize_url($url, $allowRelative = true)
{
    $url = html_entity_decode(trim((string)$url), ENT_QUOTES, 'UTF-8');
    $url = preg_replace('/[\x00-\x1F\x7F]+/u', '', $url);

    if ($url === '') {
        return '';
    }

    if (preg_match('/[\s<>"\']/', $url)) {
        return '';
    }

    if (str_starts_with($url, '//')) {
        return '';
    }

    $colonPos     = strpos($url, ':');
    $delimiterPos = strcspn($url, '/?#');
    $hasScheme    = $colonPos !== false && ($delimiterPos === strlen($url) || $colonPos < $delimiterPos);

    if ($hasScheme) {
        $scheme         = strtolower(substr($url, 0, $colonPos));
        $allowedSchemes = ['http', 'https', 'ftp', 'mailto'];
        if (!in_array($scheme, $allowedSchemes, true)) {
            return '';
        }
        // filter_var is a useful final structural check for http/https/ftp
        // (catches things like unclosed IPv6 brackets). Skip it for mailto
        // because FILTER_VALIDATE_URL rejects mailto: across PHP versions,
        // and skip for relative URLs (they fail the same check by design).
        if ($scheme !== 'mailto' && filter_var($url, FILTER_VALIDATE_URL) === false) {
            return '';
        }
    } elseif (!$allowRelative) {
        return '';
    }

    return $url;
}

/**
 * Dosya adını güvenli hale getir.
 *
 * basename() BEFORE the allowlist so "..%2f.." / "../../" encoded
 * traversal attempts are neutralised even if the caller forgot to
 * urldecode first.
 */
function xpages_safe_filename($value)
{
    $value = html_entity_decode(trim((string)$value), ENT_QUOTES, 'UTF-8');
    if ($value === '') {
        return '';
    }

    $path = parse_url($value, PHP_URL_PATH);
    if ($path !== null && $path !== false && $path !== '') {
        $value = $path;
    }

    $value = basename(str_replace('\\', '/', $value));
    $value = preg_replace('/[^A-Za-z0-9._-]/', '', $value);

    if ($value === '' || $value === '.' || $value === '..') {
        return '';
    }

    return $value;
}
