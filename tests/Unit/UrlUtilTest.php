<?php

declare(strict_types=1);

namespace XoopsModules\Xpages\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 2) . '/include/url_util.php';

/**
 * Pure-function coverage for include/url_util.php.
 *
 * Exercised patterns:
 *   - protocol-relative URLs ("//evil/")
 *   - disallowed schemes (javascript:)
 *   - allowed schemes (http/https/ftp/mailto) + relative
 *   - control-character / entity stripping
 *   - traversal-safe filenames
 *   - dotfile / empty-name rejection
 */
#[CoversFunction('xpages_normalize_url')]
#[CoversFunction('xpages_safe_filename')]
final class UrlUtilTest extends TestCase
{
    public static function normalizeUrlCases(): iterable
    {
        yield 'http'                    => ['https://example.com/path',        'https://example.com/path'];
        yield 'query string'            => ['http://example.com?q=1&a=2',      'http://example.com?q=1&a=2'];
        yield 'mailto'                  => ['mailto:user@example.com',         'mailto:user@example.com'];
        yield 'ftp'                     => ['ftp://files.example/readme',      'ftp://files.example/readme'];
        yield 'relative absolute path'  => ['/local/path',                     '/local/path'];
        yield 'relative bare'           => ['local.html',                      'local.html'];
        yield 'empty'                   => ['',                                ''];
        yield 'whitespace only'         => ['   ',                             ''];
        yield 'protocol-relative reject' => ['//evil.com/path',                ''];
        yield 'javascript reject'       => ['javascript:alert(1)',             ''];
        yield 'data reject'             => ['data:text/html,<script>',         ''];
        yield 'embedded whitespace'     => ['http://bad one.com',              ''];
        yield 'embedded quote'          => ["http://bad'one.com",              ''];
        yield 'malformed IPv6 brackets' => ['http://[malformed',               ''];
        yield 'entity-encoded script'   => ['&#106;avascript:alert(1)',        ''];
    }

    #[DataProvider('normalizeUrlCases')]
    public function testNormalizeUrl(string $input, string $expected): void
    {
        self::assertSame($expected, xpages_normalize_url($input));
    }

    public function testNormalizeUrlRejectsRelativeWhenDisabled(): void
    {
        self::assertSame('', xpages_normalize_url('/relative', false));
        self::assertSame('', xpages_normalize_url('foo.html',  false));
        self::assertSame('https://ex.com/', xpages_normalize_url('https://ex.com/', false));
    }

    public static function safeFilenameCases(): iterable
    {
        yield 'simple'                  => ['photo.jpg',             'photo.jpg'];
        yield 'with spaces stripped'    => ['my photo.jpg',          'myphoto.jpg'];
        yield 'url prefix'              => ['http://x/up/photo.jpg', 'photo.jpg'];
        yield 'backslash path'          => ['a\\b\\c.png',           'c.png'];
        yield 'traversal literal'       => ['../../etc/passwd',      'passwd'];
        // rawurldecode() runs before basename() so %-encoded traversal
        // collapses to real slashes, which basename() then consumes.
        yield 'traversal encoded'       => ['..%2f..%2fpasswd',      'passwd'];
        // Mixed-case %2F decodes to "/" the same as lowercase %2f.
        yield 'traversal encoded case'  => ['..%2F..%2Fpasswd',      'passwd'];
        yield 'dot-only reject'         => ['.',                     ''];
        yield 'double-dot reject'       => ['..',                    ''];
        yield 'empty reject'            => ['',                      ''];
        yield 'whitespace reject'       => ['   ',                   ''];
        yield 'non-ascii stripped'      => ['café.jpg',              'caf.jpg'];
        // Leading dots are stripped so dotfiles (.htaccess, .env, .git)
        // become their non-dot tail; an all-dot name rejects to empty.
        yield 'htaccess-style'          => ['.htaccess',             'htaccess'];
        yield 'env dotfile'             => ['.env',                  'env'];
        yield 'multi-dot prefix'        => ['...secret',             'secret'];
    }

    #[DataProvider('safeFilenameCases')]
    public function testSafeFilename(string $input, string $expected): void
    {
        self::assertSame($expected, xpages_safe_filename($input));
    }
}
