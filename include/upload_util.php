<?php

declare(strict_types=1);

/**
 * xPages — Upload directory + MIME validation helpers.
 *
 * @package xpages
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * Upload dizinini hazırla ve yürütülebilir dosyaları kapat.
 *
 * Ensures $dir exists and writes a .htaccess guard blocking direct
 * HTTP access to PHP/CGI/shell scripts that might be uploaded by a
 * compromised admin account.
 */
function xpages_ensure_upload_dir($dir)
{
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
            return false;
        }
    }

    $guardFile = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . '.htaccess';
    if (!file_exists($guardFile)) {
        $guardContent = "<FilesMatch \"\\.(php|phtml|phar|phps|pl|py|cgi|sh)$\">\n"
            . "    Require all denied\n"
            . "    Deny from all\n"
            . "</FilesMatch>\n";
        @file_put_contents($guardFile, $guardContent, LOCK_EX);
    }

    return true;
}

/**
 * Dosya yükleme türünü MIME ile doğrula.
 *
 * Extension-based allowlist first, then fileinfo-based MIME check if
 * available. If finfo is absent (rare on hardened hosts) we fall back
 * to extension-only — the caller has already done basename()+allowlist
 * so the attack surface is bounded.
 */
function xpages_upload_is_allowed($tmpFile, $ext)
{
    $ext            = strtolower((string)$ext);
    $allowedMimeMap = [
        'jpg'  => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png'  => ['image/png'],
        'gif'  => ['image/gif'],
        'webp' => ['image/webp'],
        'pdf'  => ['application/pdf'],
        'doc'  => ['application/msword', 'application/vnd.ms-office', 'application/octet-stream'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/octet-stream'],
        'zip'  => ['application/zip', 'application/x-zip-compressed', 'application/octet-stream'],
    ];

    if (!isset($allowedMimeMap[$ext]) || !is_file($tmpFile) || !is_readable($tmpFile)) {
        return false;
    }

    if (!function_exists('finfo_open')) {
        return true;
    }

    $finfo = @finfo_open(FILEINFO_MIME_TYPE);
    if (!$finfo) {
        return true;
    }

    $mime = @finfo_file($finfo, $tmpFile);
    @finfo_close($finfo);

    if ($mime === false || $mime === '') {
        return false;
    }

    return in_array($mime, $allowedMimeMap[$ext], true);
}
