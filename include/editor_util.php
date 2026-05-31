<?php

declare(strict_types=1);

/**
 * xPages — WYSIWYG editor rendering helpers.
 *
 * Used by the page-edit form for body / header_code / footer_code
 * textareas. Picks up TinyMCE 7, TinyMCE (legacy), CKEditor, or DHTML
 * in that preference order and falls back to a plain <textarea> if
 * none are installed.
 *
 * @package xpages
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * XOOPS editörlerini kontrol et ve listele.
 *
 * @return list<string> editor dirnames under class/xoopseditor/
 */
function xpages_get_available_editors(): array
{
    $editors    = [];
    $editorPath = XOOPS_ROOT_PATH . '/class/xoopseditor';

    if (is_dir($editorPath)) {
        $dirs = scandir($editorPath);
        foreach ($dirs as $dir) {
            if ($dir !== '.' && $dir !== '..' && is_dir($editorPath . '/' . $dir)) {
                $editors[] = $dir;
            }
        }
    }

    return $editors;
}

/**
 * Editör render et.
 *
 * Preference order: TinyMCE 7 → TinyMCE (legacy) → CKEditor → DHTML →
 * plain textarea. Every installed editor is queried through
 * XoopsEditorHandler so the same options block works for all of them.
 */
function xpages_render_editor($name, $value, $rows = 25, $cols = '100%')
{
    $editorHtml = '';

    if (file_exists(XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php')) {
        require_once XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php';

        if (class_exists('XoopsEditorHandler')) {
            $editorHandler = XoopsEditorHandler::getInstance();
            $editors       = $editorHandler->getList();

            if (isset($editors['tinymce']) || isset($editors['tinymce7'])) {
                $editorType = isset($editors['tinymce7']) ? 'tinymce7' : 'tinymce';
                $editor = $editorHandler->get($editorType, [
                    'name'   => $name,
                    'value'  => $value,
                    'rows'   => $rows,
                    'cols'   => $cols,
                    'width'  => '100%',
                    'height' => '400px',
                ]);
                if ($editor && method_exists($editor, 'render')) {
                    $editorHtml = $editor->render();
                }
            } elseif (isset($editors['ckeditor'])) {
                $editor = $editorHandler->get('ckeditor', [
                    'name'  => $name,
                    'value' => $value,
                    'rows'  => $rows,
                    'cols'  => $cols,
                ]);
                if ($editor && method_exists($editor, 'render')) {
                    $editorHtml = $editor->render();
                }
            } elseif (isset($editors['dhtml'])) {
                $editor = $editorHandler->get('dhtml', [
                    'name'  => $name,
                    'value' => $value,
                    'rows'  => $rows,
                    'cols'  => $cols,
                ]);
                if ($editor && method_exists($editor, 'render')) {
                    $editorHtml = $editor->render();
                }
            }
        }
    }

    if (empty($editorHtml)) {
        $editorHtml = '<textarea name="' . htmlspecialchars((string)$name, ENT_QUOTES) . '" '
            . 'id="' . htmlspecialchars((string)$name, ENT_QUOTES) . '" '
            . 'rows="' . (int)$rows . '" class="xp-code-textarea">'
            . htmlspecialchars((string)$value, ENT_QUOTES) . '</textarea>';
    }

    return $editorHtml;
}
