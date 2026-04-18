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
 * Pipeline: entity-decode → url-decode → extract URL path → basename
 * of the backslash-normalised path → alphanum-allowlist → strip
 * leading dots → reject `.` / `..` / empty.
 *
 * rawurldecode() before basename catches encoded traversal like
 * `..%2f..%2fetc%2fpasswd` that would otherwise survive as a single
 * filename through the allowlist. Leading-dot stripping blocks
 * `.htaccess`-style dotfiles. Both are called out as defensive
 * requirements in ~/.claude/CLAUDE.md "Filename sanitization".
 */
function xpages_safe_filename($value)
{
    $value = html_entity_decode(trim((string)$value), ENT_QUOTES, 'UTF-8');
    if ($value === '') {
        return '';
    }

    // Decode %-encoded bytes so e.g. ..%2f..%2fpasswd collapses to
    // ../../passwd before basename() extracts the last segment.
    $value = rawurldecode($value);

    $path = parse_url($value, PHP_URL_PATH);
    if ($path !== null && $path !== false && $path !== '') {
        $value = $path;
    }

    $value = basename(str_replace('\\', '/', $value));
    $value = preg_replace('/[^A-Za-z0-9._-]/', '', $value);

    // Strip any leading dots so .htaccess / .env / .git-style dotfiles
    // are rejected or reduced to their non-dot tail.
    $value = ltrim($value, '.');

    if ($value === '' || $value === '.' || $value === '..') {
        return '';
    }

    return $value;
}
