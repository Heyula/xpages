<?php

declare(strict_types=1);

/**
 * xPages — Back-compat aggregator for the split helper modules.
 *
 * Everything that used to live here has been moved into focused files
 * under include/ (handler_helpers, url_util, upload_util, tree_util,
 * template_util, editor_util). Existing callers that do
 *   require_once XOOPS_ROOT_PATH . '/modules/xpages/include/functions.php';
 * continue to work unchanged — every function they depend on still
 * resolves through this aggregator.
 *
 * New code should prefer including the specific helper file it needs.
 *
 * @package xpages
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

require_once __DIR__ . '/handler_helpers.php';
require_once __DIR__ . '/url_util.php';
require_once __DIR__ . '/upload_util.php';
require_once __DIR__ . '/tree_util.php';
require_once __DIR__ . '/template_util.php';
require_once __DIR__ . '/editor_util.php';
