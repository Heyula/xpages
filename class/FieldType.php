<?php

declare(strict_types=1);

namespace XoopsModules\Xpages;

/**
 * xPages — Backed enum of supported extra-field input types.
 *
 * The string values mirror the `field_type` column stored on
 * xpages_fields rows. Any new case must also have a branch in the
 * xpages_field_input.tpl partial and (if it needs descriptor extras)
 * in the match() inside xpages_build_field_descriptor().
 *
 * @package  xpages
 */
enum FieldType: string
{
    case Text     = 'text';
    case Textarea = 'textarea';
    case Email    = 'email';
    case Url      = 'url';
    case Tel      = 'tel';
    case Number   = 'number';
    case Checkbox = 'checkbox';
    case Select   = 'select';
    case Radio    = 'radio';
    case File     = 'file';
}
