<?php

declare(strict_types=1);

namespace XoopsModules\Xpages;

/**
 * xPages — Read-only descriptor for a single extra-field input.
 *
 * Replaces the associative array previously built by
 * xpages_build_field_descriptor(). Public properties are accessed
 * directly from the Smarty partial (`$field.type`, `$field.options`,
 * etc.) so no template changes were required when this class was
 * introduced.
 *
 * Type-specific properties (options, checked, file_*) are nullable /
 * defaulted so callers for unrelated field types can simply omit them.
 *
 * @package  xpages
 */
readonly class FieldDescriptor
{
    /**
     * @param array<int,array{value:string,label:string,selected:bool,radio_id:string}> $options
     *     select / radio options, each pre-formatted for the template.
     * @param array{current_file:string,replace_note:string,file_none:string} $labels
     *     File-input translated labels (populated for File type only).
     */
    public function __construct(
        public int    $id,
        public string $type,
        public string $name,
        public string $input_id,
        public string $label,
        public string $desc,
        public bool   $required,
        public string $value = '',
        // select / radio:
        public array  $options = [],
        public string $placeholder = '',
        // checkbox:
        public bool   $checked = false,
        // file:
        public string $file_input_name = '',
        public string $file_input_id = '',
        public array  $labels = [],
        public bool   $has_current_file = false,
        public string $current_file_url = '',
        public string $current_file_raw = '',
        public string $current_file_safe = '',
        public bool   $is_image = false,
    ) {}
}
