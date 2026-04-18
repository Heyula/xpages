<?php

declare(strict_types=1);

namespace XoopsModules\Xpages;

/**
 * xPages — Field-value value object.
 *
 * Namespaced M10 rewrite. The handler is in FieldvalueHandler.php and
 * is loaded on demand via the preloads/autoloader.
 *
 * @package  xpages
 * @author   Eren Yumak — Aymak (aymak.net)
 */
class Fieldvalue extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('value_id',    XOBJ_DTYPE_INT,    null, false);
        $this->initVar('page_id',     XOBJ_DTYPE_INT,    0,    false);
        $this->initVar('field_id',    XOBJ_DTYPE_INT,    0,    false);
        $this->initVar('field_value', XOBJ_DTYPE_TXTAREA, '',   false);
    }
}
