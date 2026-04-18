<?php
/**
 * xPages — Admin sayfa listesi
 * @package  xpages
 * @author   Eren Yumak — Aymak (aymak.net)
 */

use Xmf\Request;

include_once '../../../include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/modules/xpages/include/functions.php';
xpages_admin_boot();

xoops_cp_header();
xpages_admin_register_css();

if (class_exists('Xmf\\Module\\Admin')) {
    \Xmf\Module\Admin::getInstance()->displayNavigation('pages.php');
}

$pageHandler = xpages_get_handler('page');

if (!$pageHandler) {
    echo '<div class="xp-alert xp-alert--error">xPages handler unavailable.</div>';
    xoops_cp_footer();
    exit;
}

// ── Silme işlemi ──────────────────────────────────────────────────────────────
if (Request::getCmd('op', '', 'GET') === 'delete' && Request::getInt('page_id', 0, 'GET') > 0) {
    $pageId = Request::getInt('page_id', 0, 'GET');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || 1 !== Request::getInt('confirm', 0, 'POST')) {
        $pageObj = $pageHandler->get($pageId);
        if ($pageObj) {
            echo '<div class="xp-alert xp-alert--warning">';
            echo '<p>⚠️ ' . sprintf(_AM_XPAGES_DELETE_CONFIRM, htmlspecialchars((string)$pageObj->getVar('title'), ENT_QUOTES)) . '</p>';
            echo '<form method="post" action="pages.php?op=delete&page_id=' . $pageId . '" class="xp-confirm-actions">';
            echo '<input type="hidden" name="op" value="delete">';
            echo '<input type="hidden" name="page_id" value="' . $pageId . '">';
            echo '<input type="hidden" name="confirm" value="1">';
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo '<button type="submit" class="xp-btn xp-btn--danger">' . _AM_XPAGES_YES . '</button>';
            echo '<a href="pages.php" class="xp-btn xp-btn--cancel">' . _AM_XPAGES_NO . '</a>';
            echo '</form></div>';
        }
        xoops_cp_footer();
        exit;
    }

    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('pages.php', 3, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
        exit;
    }

    $pageObj = $pageHandler->get($pageId);
    if ($pageObj) {
        xpages_delete_page_data($pageId);
        $pageHandler->delete($pageObj);
        redirect_header('pages.php', 2, _AM_XPAGES_PAGE_DELETED);
        exit;
    }
    redirect_header('pages.php', 2, _AM_XPAGES_PAGE_NOT_FOUND);
    exit;
}

// ── Durum değiştir ────────────────────────────────────────────────────────────
if (Request::getCmd('op', '', 'POST') === 'toggle' && Request::getInt('page_id', 0, 'POST') > 0) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('pages.php', 3, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
        exit;
    }

    $pageObj = $pageHandler->get(Request::getInt('page_id', 0, 'POST'));
    if ($pageObj) {
        $pageObj->setVar('page_status', (int)!$pageObj->getVar('page_status'));
        $pageHandler->insert($pageObj);
    }
    redirect_header('pages.php', 0, '');
    exit;
}

// ── Liste ─────────────────────────────────────────────────────────────────────
$criteria = new CriteriaCompo();
$criteria->setSort('menu_order');
$criteria->setOrder('ASC');
$pages = $pageHandler->getObjects($criteria) ?: [];

echo '<div class="xp-toolbar">';
echo '<h2>📄 ' . _AM_XPAGES_MENU_PAGES . '</h2>';
echo '<a href="page_edit.php" class="xp-btn xp-btn--add">➕ ' . _AM_XPAGES_MENU_ADD_PAGE . '</a>';
echo '</div>';

if (count($pages) > 0) {
    echo '<div class="xp-table-wrap">';
    echo '<table class="xp-table">';
    echo '<thead><tr>';
    echo '<th>ID</th>';
    echo '<th>' . _AM_XPAGES_PAGE_TITLE . '</th>';
    echo '<th>URL Alias</th>';
    echo '<th class="xp-cell-center">' . _AM_XPAGES_PAGE_STATUS . '</th>';
    echo '<th class="xp-cell-center">Sort Order</th>';
    echo '<th class="xp-cell-center">' . _AM_XPAGES_ACTIONS . '</th>';
    echo '</tr></thead><tbody>';

    foreach ($pages as $p) {
        $pid    = (int)$p->getVar('page_id');
        $status = (int)$p->getVar('page_status');

        // Sayfa URL'ini oluştur
        $alias = $p->getVar('alias', 'n');
        if (!empty($alias)) {
            $pageUrl = XOOPS_URL . '/modules/xpages/page.php?alias=' . urlencode($alias);
        } else {
            $pageUrl = XOOPS_URL . '/modules/xpages/page.php?page_id=' . $pid;
        }

        echo '<tr>';
        echo '<td>' . $pid . '</td>';
        echo '<td><strong>' . htmlspecialchars((string)$p->getVar('title'), ENT_QUOTES) . '</strong></td>';
        echo '<td><code class="xp-alias-code">' . htmlspecialchars($alias, ENT_QUOTES) . '</code></td>';
        echo '<td class="xp-cell-center">';
        echo '<form method="post" action="pages.php">';
        echo '<input type="hidden" name="op" value="toggle">';
        echo '<input type="hidden" name="page_id" value="' . $pid . '">';
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo '<button type="submit" title="' . _AM_XPAGES_TOGGLE_STATUS_TITLE . '" class="xp-btn--unstyled">';
        echo $status ? '✅ Aktif' : '❌ Pasif';
        echo '</button></form></td>';
        echo '<td class="xp-cell-center">' . (int)$p->getVar('menu_order') . '</td>';
        echo '<td class="xp-cell-center"><div class="xp-actions">';
        echo '<a href="page_edit.php?page_id=' . $pid . '" class="xp-action--edit" title="' . _AM_XPAGES_EDIT . '">✏️ ' . _AM_XPAGES_EDIT . '</a>';
        echo '<a href="' . $pageUrl . '" target="_blank" class="xp-action--view" title="' . _AM_XPAGES_PAGETO . '">👁️ ' . _AM_XPAGES_PAGETO . '</a>';
        echo '<a href="pages.php?op=delete&page_id=' . $pid . '" class="xp-action--delete" title="' . _AM_XPAGES_DELETE . '">🗑️ ' . _AM_XPAGES_DELETE . '</a>';
        echo '</div></td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';

    echo '<div class="xp-alert xp-alert--info">';
    echo '📊 ' . sprintf(_AM_XPAGES_STAT_PAGES, count($pages));
    echo '</div>';

} else {
    echo '<div class="xp-empty">';
    echo '<div class="xp-empty-icon">📭</div>';
    echo '<div class="xp-empty-text">' . _AM_XPAGES_NO_PAGES . '</div>';
    echo '<a href="page_edit.php" class="xp-empty-cta">' . _AM_XPAGES_CREATE_FIRST . '</a>';
    echo '</div>';
}

xoops_cp_footer();
