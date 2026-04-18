/**
 * xPages — Admin UI behaviours.
 *
 * Currently only the tab switcher for admin/page_edit.php. Registered
 * globally for all admin pages from xpages_admin_register_css(); the
 * listener is a no-op on pages that don't contain `.xp-tabs`.
 *
 * Delegation target: any <a> whose closest `<ul class="xp-tabs">` exists
 * in the document. Click handling:
 *   - prevent the default `#tab-foo` anchor jump
 *   - resolve the target pane id from the link's href hash
 *   - swap the `active` class on the clicked <li> + matching .xp-tab-pane
 */

(function () {
    'use strict';

    function showTab(anchor) {
        const hash = anchor.getAttribute('href') || '';
        const id   = hash.startsWith('#') ? hash.slice(1) : hash;
        if (!id) return;

        const pane = document.getElementById(id);
        if (!pane) return;

        const tabList = anchor.closest('ul.xp-tabs');
        if (!tabList) return;

        tabList.querySelectorAll('li').forEach(function (li) {
            li.classList.remove('active');
        });
        // Scope the pane-swap to tab-panes adjacent to this tab list
        // (siblings or descendants of the same form/container).
        const scope = tabList.parentElement || document;
        scope.querySelectorAll('.xp-tab-pane').forEach(function (p) {
            p.classList.remove('active');
        });

        pane.classList.add('active');
        const li = anchor.closest('li');
        if (li) li.classList.add('active');
    }

    function onClick(event) {
        const anchor = event.target.closest('ul.xp-tabs a[href^="#"]');
        if (!anchor) return;
        event.preventDefault();
        showTab(anchor);
    }

    document.addEventListener('click', onClick, false);
})();
