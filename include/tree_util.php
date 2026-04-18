<?php

declare(strict_types=1);

/**
 * xPages — Page-tree traversal + cascade-delete helpers.
 *
 * Depends on:
 *   - xpages_get_handler()       (handler_helpers.php)
 *   - xpages_safe_filename()     (url_util.php)
 *
 * @package xpages
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * Sayfanın tüm alt sayfa ID'lerini topla.
 *
 * Recursive descent through the parent_id → children graph. Guarded
 * with a $visited map so a mis-inserted cycle in the page tree
 * doesn't infinite-loop.
 */
function xpages_collect_descendant_ids($pageHandler, $pageId, array &$descendantIds, array &$visited = []): void
{
    $pageId = (int)$pageId;
    if ($pageId <= 0 || isset($visited[$pageId]) || !$pageHandler) {
        return;
    }

    $visited[$pageId] = true;

    $criteria = new Criteria('parent_id', $pageId);
    $children = $pageHandler->getObjects($criteria) ?: [];

    foreach ($children as $child) {
        $childId = (int)$child->getVar('page_id');
        if ($childId <= 0) {
            continue;
        }
        if (!in_array($childId, $descendantIds, true)) {
            $descendantIds[] = $childId;
        }
        xpages_collect_descendant_ids($pageHandler, $childId, $descendantIds, $visited);
    }
}

/**
 * Sayfa verilerini sil.
 *
 * Cascading delete — removes sub-pages (recursively), field values,
 * per-page field definitions (including any uploaded file attachments),
 * and gallery items. Also guarded with a $visited map so a cycle in
 * the page tree can't cause infinite recursion during deletion.
 */
function xpages_delete_page_data($pageId, array &$visited = []): void
{
    $pageHandler    = xpages_get_handler('page');
    $valueHandler   = xpages_get_handler('fieldvalue');
    $fieldHandler   = xpages_get_handler('field');
    $galleryHandler = xpages_get_handler('gallery');

    $pageId = (int)$pageId;
    if ($pageId <= 0 || isset($visited[$pageId]) || !$pageHandler || !$valueHandler || !$fieldHandler) {
        return;
    }
    $visited[$pageId] = true;

    // Alt sayfaları bul
    $criteria = new Criteria('parent_id', $pageId);
    $subPages = $pageHandler->getObjects($criteria) ?: [];
    foreach ($subPages as $subPage) {
        xpages_delete_page_data($subPage->getVar('page_id'), $visited);
        $pageHandler->delete($subPage);
    }

    // Alan değerlerini sil
    $valueHandler->deleteValuesForPage($pageId);

    // Sayfaya özel alan tanımlarını sil
    $criteria = new Criteria('page_id', $pageId);
    $fields   = $fieldHandler->getObjects($criteria) ?: [];
    foreach ($fields as $field) {
        if ($field->getVar('field_type') === 'file' && !empty($field->getVar('field_default'))) {
            $safeFile = xpages_safe_filename($field->getVar('field_default', 'n'));
            $filePath = $safeFile !== '' ? XOOPS_UPLOAD_PATH . '/xpages/' . $safeFile : '';
            if ($filePath !== '' && file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        $fieldHandler->delete($field);
    }

    // Galeri verilerini sil
    if ($galleryHandler) {
        $galleryHandler->deleteGalleryForPage($pageId);
    }
}
