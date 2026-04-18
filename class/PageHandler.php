<?php

declare(strict_types=1);

namespace XoopsModules\Xpages;

/**
 * xPages — Page handler.
 *
 * Companion to Page.php. The preloads/autoloader loads this file on
 * demand when Helper::getHandler('page') resolves it via
 * XoopsModules\Xpages\PageHandler.
 *
 * @package  xpages
 * @author   Eren Yumak — Aymak (aymak.net)
 */
class PageHandler extends \XoopsPersistableObjectHandler
{
    public function __construct(\XoopsDatabase $db)
    {
        // Page::class resolves to the FQCN in this namespace
        // (XoopsModules\Xpages\Page), which is what the parent's
        // create() / getObjects() need for `new $className()`.
        parent::__construct($db, 'xpages_pages', Page::class, 'page_id', 'title');
    }

    /**
     * Alias ile sayfa bul
     */
    public function getByAlias(string $alias): ?Page
    {
        $criteria = new \Criteria('alias', $alias);
        $objects  = $this->getObjects($criteria);
        return !empty($objects) ? $objects[0] : null;
    }

    /**
     * Menüde gösterilecek sayfaları getir
     *
     * @return Page[]
     */
    public function getMenuPages(int $parentId = 0, bool $onlyActive = true): array
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('parent_id', $parentId));
        $criteria->add(new \Criteria('show_in_menu', 1));

        if ($onlyActive) {
            $criteria->add(new \Criteria('page_status', 1));
        }

        $criteria->setSort('menu_order');
        $criteria->setOrder('ASC');

        return $this->getObjects($criteria) ?: [];
    }

    /**
     * Benzersiz alias oluştur
     */
    public function generateAlias(string $title, int $excludeId = 0): string
    {
        $alias    = $this->cleanAlias($title);
        $original = $alias;
        $counter  = 1;

        while ($this->aliasExists($alias, $excludeId)) {
            $alias = $original . '-' . $counter;
            $counter++;
        }

        return $alias;
    }

    /**
     * Alias temizleme
     */
    private function cleanAlias(string $str): string
    {
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', '-', $str);
        $str = trim($str, '-');
        return $str !== '' ? $str : 'page';
    }

    /**
     * Alias var mı kontrol et
     */
    private function aliasExists(string $alias, int $excludeId = 0): bool
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('alias', $alias));
        if ($excludeId > 0) {
            $criteria->add(new \Criteria('page_id', $excludeId, '!='));
        }
        return $this->getCount($criteria) > 0;
    }

    /**
     * Hit sayısını artır.
     *
     * Deliberately bypasses the XoopsObject layer and issues a bare
     * `UPDATE hits = hits + 1` so concurrent hits are atomic at the
     * InnoDB row level — no read-modify-write race that could lose a
     * hit on a high-traffic page.
     *
     * Side-effect to be aware of: the in-memory Page object the caller
     * may be holding is NOT refreshed. `$page->getVar('hits')`
     * immediately after this call still reflects the pre-increment
     * value. Re-`get()` the page if the fresh count matters.
     *
     * Also skips the `update_date` timestamp so counting hits doesn't
     * muddle last-edited dates on the page list.
     */
    public function incrementHits(int $pageId): bool
    {
        $sql = "UPDATE {$this->table} SET hits = hits + 1 WHERE page_id = " . $pageId;
        // XOOPS 2.7: exec() for mutating statements (queryF is deprecated).
        return (bool)$this->db->exec($sql);
    }
}
