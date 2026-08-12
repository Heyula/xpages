# xPages Tutorial

This tutorial explains `xPages` from two angles:

1. how a site administrator or content editor can use it effectively
2. how a developer can understand the module structure and build something similar

`xPages` is a static-page and structured-content module for XOOPS. It combines:

- a page system
- hierarchical navigation
- SEO metadata
- custom extra fields
- an optional image gallery
- blocks, search, and comments integration

It is a good fit when you need content pages that are more flexible than a plain article, but lighter than a full CMS-within-a-CMS.

## Part 1. User Tutorial

## 1. What xPages is good for

Use `xPages` when you want to publish pages such as:

- About us
- Services
- Product landing pages
- Documentation pages
- Team pages
- Contact or support pages
- Download pages
- Policy or legal pages

The main benefits are:

- pages are easy to organize in a menu structure
- each page can have its own SEO settings
- each page can use extra structured fields
- pages can include a simple image gallery
- content stays inside XOOPS permissions, themes, comments, and search

## 2. What you manage in xPages

The module revolves around four content areas:

- `Pages`
  The main content records. Each page has title, alias, body, status, order, parent page, and SEO settings.

- `Fields`
  Reusable custom fields. These can be global or page-specific.

- `Field values`
  The actual saved values for those custom fields on each page.

- `Gallery`
  Optional images attached to a page.

## 3. Before you begin

Make sure:

- the module is installed and active in XOOPS
- you have module admin rights for `xPages`
- the XOOPS upload directory is writable, because `xPages` stores file and gallery uploads there

Relevant module settings in preferences:

- `items_per_page`
- `allow_comments`
- `meta_keywords`
- `meta_description`
- `show_breadcrumb`
- `show_lastmod`

These are global defaults. Individual pages can still define their own metadata.

## 4. First-time setup workflow

A practical first setup looks like this:

1. Review module preferences.
2. Create your first top-level pages.
3. Decide whether you need custom fields.
4. Add a gallery only on pages that really need visual content.
5. Add one or both blocks to your theme.
6. Test page URLs, menu structure, metadata, and comments.

## 5. Creating your first page

Go to the `Pages` area in the module admin.

For each new page:

1. Click `Add Page`.
2. Enter the `Title`.
3. Optionally enter an `Alias`.
   If you leave it blank, xPages generates one from the title.
4. Enter `Short Description`.
   This is useful for listing pages, blocks, and previews.
5. Enter the main `Body`.
6. Choose whether the page is `Active` or `Inactive`.
7. Set `Page Order`.
8. Decide whether it appears in:
   - menu
   - navigation
9. Choose a `Parent Page` if this page should appear under another page.
10. Save.

Why this matters:

- `Title` is for readers and SEO
- `Alias` controls a cleaner URL
- `Short Description` helps page listings and recent-page blocks
- `Parent Page` creates a hierarchy
- `Page Order` controls display order

## 6. Understanding page hierarchy

`xPages` supports parent-child relationships.

That lets you create structures like:

- Services
  - Consulting
  - Training
  - Support

Benefits:

- better navigation
- cleaner menu blocks
- more logical site structure
- easier grouping of related pages

The module also prevents invalid parent selection loops when editing a page.

## 7. SEO options on each page

Each page can define:

- `Meta Title`
- `Meta Keywords`
- `Meta Description`
- `Noindex`
- `Nofollow`
- `Redirect URL`

How to use them:

- Use `Meta Title` when you want a browser title different from the page title.
- Use `Meta Description` for search-result descriptions.
- Use `Noindex` for pages you do not want indexed by search engines.
- Use `Nofollow` for pages that should not pass crawler trust to their links.
- Use `Redirect URL` when a page should send visitors elsewhere instead of showing local content.

Practical examples:

- old landing page that now points to a new URL
- legal page you want accessible but not indexed
- campaign page with custom title and description

## 8. Header code and footer code

Advanced users can add:

- `Header Code`
- `Footer Code`

These are intended for page-specific snippets such as:

- analytics events
- embed scripts
- custom tracking
- third-party widgets

Use this carefully.

Only module admins should use it, because these fields inject raw code into the page output.

If you do not need custom code, leave these fields empty.

## 9. Creating custom fields

Custom fields are one of the most useful parts of `xPages`.

You can use them for content such as:

- phone number
- email address
- button URL
- PDF attachment
- staff role
- product code
- specification list
- contact details

Available field types include:

- text
- textarea
- email
- url
- tel
- number
- checkbox
- radio
- select
- file/image

There are two patterns:

- `Global fields`
  Available on all pages.

- `Page-specific fields`
  Available only for one page.

This makes the module flexible. For example:

- global fields for contact information or download button labels
- page-specific fields for a single product page or team page

## 10. How to add a custom field

Go to `Fields`.

Then:

1. Choose whether you are adding a global field or a field tied to a page.
2. Click `Add Field`.
3. Fill in:
   - field name
   - field label
   - field type
   - options if needed
   - default value
   - description
   - order
   - status
   - required or optional
   - show in template or not
4. Save.

Notes:

- `Field name` should be simple and machine-friendly.
- `Field label` is what users see.
- `Field options` is used for `radio` and `select`.
- `show_in_tpl` controls whether the field is exposed to the public template output.

For `radio` and `select`, enter one option per line.

Example:

```text
Basic
Professional
Enterprise
```

## 11. Using file and image fields

Field type `file` supports uploads such as:

- image files
- PDF documents
- DOC or DOCX files
- ZIP files

Typical use cases:

- brochure download
- datasheet
- resume
- signed form
- page-specific image

The module validates uploads by extension and MIME type and stores them under the XOOPS uploads area for `xPages`.

Best practice:

- use file fields only when the file belongs to the content model of the page
- use the gallery when you want multiple display images for a page

## 12. Saving field values on a page

When a page has custom fields, the page edit screen automatically shows those fields.

You fill them in while editing the page.

Examples:

- page title: `Consulting`
- extra field `contact_email`: `sales@example.com`
- extra field `brochure_pdf`: uploaded PDF
- extra field `service_level`: `Professional`

This is the point where `Fields` become actual `Field values`.

## 13. Adding a gallery

Use the gallery when a page needs multiple images.

Examples:

- company office photos
- event images
- screenshots
- portfolio items

How to add gallery images:

1. Open the page in admin.
2. Save the page first if it is new.
3. Open `Gallery`.
4. Add a new item.
5. Enter:
   - title
   - description
   - order
   - status
6. Choose either:
   - local upload
   - external image URL
7. Save.

Tips:

- use local uploads for stable content
- use external URLs only when you trust the external source
- keep the gallery ordered cleanly for better presentation

## 14. Managing page status

Every page and gallery item has status control.

Use this for:

- drafting content
- temporarily hiding content
- scheduling manual publication
- preserving content without deleting it

The `Pages` list lets you quickly toggle page status.

## 15. Deleting pages, fields, and gallery items

Deletion is protected with confirmation and CSRF validation.

Be careful:

- deleting a page also removes related data
- deleting a field can remove stored values for that field
- deleting gallery items can remove uploaded files

Recommended practice:

- set content inactive first if you are unsure
- delete only when you are certain it is no longer needed

## 16. Using the frontend

`xPages` has two main public entry points:

- `index.php`
  Lists visible top-level pages.

- `page.php`
  Displays a single page by:
  - alias
  - page ID

This gives you:

- a page list
- clean per-page access
- friendly links if aliases are used

## 17. Using blocks

The module includes two blocks:

- `Recent Pages`
- `Menu`

### Recent Pages block

Use it when you want:

- updated pages on the homepage
- a sidebar list of recent static content
- optional short descriptions

### Menu block

Use it when you want:

- a structured page menu
- parent-child navigation
- a sidebar or footer content tree

Benefits:

- content pages become easier to discover
- your static content becomes part of the theme layout

## 18. Search and comments

`xPages` integrates with:

- XOOPS search
- XOOPS comments

That means:

- active pages can appear in site search
- individual pages can participate in comments when comments are enabled in module preferences

This makes `xPages` feel like a normal XOOPS content module instead of a standalone page store.

## 19. Suggested editorial workflow

A practical workflow for content teams:

1. Create page structure first.
2. Add global fields only if they are reusable.
3. Add page-specific fields only for special cases.
4. Write short descriptions for all pages.
5. Add SEO metadata for public pages.
6. Use aliases consistently.
7. Use galleries sparingly.
8. Use blocks to expose the important content.

## 20. Common mistakes to avoid

- creating too many custom fields before you know your content model
- mixing gallery images and file fields for the same purpose
- forgetting to set `show_in_menu` or `show_in_nav`
- leaving aliases inconsistent
- using advanced header/footer code when a normal field would be enough
- deleting fields before checking which pages depend on them

## Part 2. Developer Tutorial

## 21. What kind of module xPages is

From a developer perspective, `xPages` is a good XOOPS module example because it combines:

- classic `XoopsObject` plus `XoopsPersistableObjectHandler`
- frontend pages
- admin CRUD
- module preferences
- templates
- blocks
- comments
- search integration
- install/update/uninstall hooks
- upload handling

It is not fully service-container or repository driven. It stays close to the classic XOOPS module model, but with cleaner helper splitting than many older modules.

That makes it a strong learning module if you want to understand how a modernized traditional XOOPS module is put together.

## 22. The core data model

The SQL schema in [sql/mysql.sql](../sql/mysql.sql) defines four tables:

- `xpages_pages`
- `xpages_fields`
- `xpages_field_values`
- `xpages_gallery`

### `xpages_pages`

This is the main content table.

It stores:

- title
- alias
- body
- short description
- status
- menu flags
- parent relationship
- author and timestamps
- SEO metadata
- redirect URL
- header/footer code
- hits
- comments count

Think of it as the central aggregate for content pages.

### `xpages_fields`

This stores custom field definitions.

Important columns:

- `page_id`
  `0` means global field, otherwise page-specific field
- `field_name`
- `field_label`
- `field_type`
- `field_options`
- `field_required`
- `field_order`
- `field_status`
- `field_default`
- `show_in_tpl`

This is the schema layer for structured content.

### `xpages_field_values`

This stores actual values for a page-field pair.

Important point:

- field definitions and field values are split cleanly
- unique key `(page_id, field_id)` prevents duplicate value rows

That is a common and useful pattern when building extensible content systems.

### `xpages_gallery`

This stores image-gallery items linked to a page.

It is separate from file fields because the gallery is a repeatable visual collection, not a single data field.

## 23. The object and handler layer

The classic XOOPS model is still the backbone:

- `class/page.php`
- `class/pagehandler.php`
- `class/field.php`
- `class/fieldhandler.php`
- `class/fieldvalue.php`
- `class/fieldvaluehandler.php`
- `class/gallery.php`
- `class/galleryhandler.php`

Pattern:

- the object class defines the fields with `initVar()`
- the handler class defines retrieval and persistence logic

Examples:

- [page.php](../class/page.php)
  Defines the page object and helper methods such as `getPageUrl()` and `getRobots()`

- [pagehandler.php](../class/pagehandler.php)
  Adds module-specific behavior such as:
  - `getByAlias()`
  - `getMenuPages()`
  - `generateAlias()`
  - `incrementHits()`

- [fieldvaluehandler.php](../class/fieldvaluehandler.php)
  Handles persistence for per-page field values, including cleanup of old uploaded files

What a developer should learn here:

- how XOOPS handlers wrap table access
- how to add helper methods that belong close to the persistence layer
- when a method belongs on the object vs on the handler

## 24. Why the helper split matters

The file [include/functions.php](../include/functions.php) is only a compatibility aggregator now.

Real helper logic was split into focused files:

- `handler_helpers.php`
- `url_util.php`
- `upload_util.php`
- `tree_util.php`
- `template_util.php`
- `editor_util.php`

This is one of the most useful structural lessons in the module.

Instead of one giant utility file, the code was separated by responsibility:

- handler resolution and admin boot
- URL sanitization
- upload validation
- tree traversal
- template preparation
- editor integration

If you build a similar module, do this early. It keeps controllers smaller and keeps responsibilities easy to find.

## 25. Public entry points

The main public scripts are:

- [index.php](../index.php)
- [page.php](../page.php)

### `index.php`

Responsibilities:

- load XOOPS bootstrap
- set the page-list template
- query active top-level pages
- paginate results
- assign a simplified list to Smarty

This is a straightforward list controller.

### `page.php`

Responsibilities:

- resolve page by alias or page ID
- validate visibility
- process redirects
- increment hits
- assign page data and gallery data
- assign SEO metadata
- enable comments if configured

This is the more important public controller because it coordinates:

- routing
- metadata
- structured field output
- gallery output
- comments integration

If you build a similar module, this file is a good example of what a single-page frontend controller in XOOPS needs to do.

## 26. Admin controllers

The main admin scripts are:

- [admin/pages.php](../admin/pages.php)
- [admin/page_edit.php](../admin/page_edit.php)
- [admin/fields.php](../admin/fields.php)
- [admin/gallery.php](../admin/gallery.php)
- [admin/index.php](../admin/index.php)

### `admin/pages.php`

Handles:

- page listing
- page status toggle
- page deletion with confirmation

### `admin/page_edit.php`

Handles:

- create and edit page form
- alias generation
- page hierarchy validation
- save logic
- extra field value collection
- uploaded extra file processing

This is the most important admin file if you want to understand the full editor workflow.

### `admin/fields.php`

Handles:

- global and page-specific field definitions
- type-specific field options
- file-type field defaults
- field deletion and cleanup

### `admin/gallery.php`

Handles:

- image or URL-based gallery items
- upload validation
- deletion cleanup

## 27. Why template preparation is done in PHP

The module does a good amount of template shaping in [template_util.php](../include/template_util.php).

This is important.

Instead of sending raw objects and letting Smarty do too much logic, the module builds template-ready structures such as:

- field descriptors for admin forms
- page field display arrays
- gallery arrays

Benefits:

- templates stay simpler
- output logic is easier to test
- security-sensitive normalization happens before rendering

Examples:

- `xpages_build_field_descriptor()`
  Converts a field definition into a renderable input model

- `xpages_assign_page()`
  Converts a page, fields, field values, and gallery items into frontend template data

If you want to create a similar module, learn this pattern. It is better than assembling raw HTML strings inside PHP controllers.

## 28. URL and file safety

Two files are especially important from a security and correctness perspective:

- [url_util.php](../include/url_util.php)
- [upload_util.php](../include/upload_util.php)

### `xpages_normalize_url()`

This validates and normalizes URLs.

It:

- decodes entities
- strips control characters
- rejects unsafe characters
- rejects protocol-relative URLs
- allows only selected schemes
- can allow or disallow relative URLs

That is a reusable pattern for any XOOPS module that accepts links or redirects.

### `xpages_safe_filename()`

This strips unsafe path content and keeps only a safe basename.

That is essential whenever you later concatenate a file value into an uploads path.

### `xpages_upload_is_allowed()`

This validates uploads by:

- extension allowlist
- MIME type via `finfo`

That is another useful reusable pattern.

## 29. Upload directories and install hooks

Install and update logic lives in [include/install.php](../include/install.php), [include/update.php](../include/update.php), and [include/uninstall.php](../include/uninstall.php).

Important behavior:

- upload directories are created automatically
- protective `.htaccess` files are written
- the gallery table is created if missing during update
- uninstall removes module-owned upload trees

This is a good example of where lifecycle code belongs in a XOOPS module:

- install-time filesystem prep
- update-time schema repair
- uninstall cleanup

## 30. Blocks, search, and comments integration

`xPages` integrates with core XOOPS systems.

### Blocks

File: [blocks/xpages_blocks.php](../blocks/xpages_blocks.php)

Included blocks:

- recent pages
- menu tree

This shows how to expose module content outside the module itself.

### Search

File: [include/search.php](../include/search.php)

This shows how to connect module content into XOOPS global search.

### Comments

Configured in [xoops_version.php](../xoops_version.php) and supported through:

- `hasComments`
- callback file
- callback function

This is important if you want your module to behave like a first-class XOOPS content module.

## 31. How handlers are resolved

The module uses:

- [class/Helper.php](../class/Helper.php)
- `xpages_get_handler()`

This is the standard XMF/XOOPS helper-driven approach.

Why it matters:

- controllers do not instantiate handlers manually
- handler naming stays conventional
- module path and dirname logic stays centralized

If you build something similar, keep handler access behind a small helper function. It reduces duplication and makes future refactoring easier.

## 32. How page rendering works end to end

A simplified request flow for a frontend page:

1. `page.php` receives `alias` or `page_id`.
2. `pagehandler` resolves the page.
3. The page is validated as active.
4. Redirect logic is applied if needed.
5. Hits are incremented.
6. `xpages_assign_page()` gathers:
   - page core fields
   - custom fields
   - field values
   - gallery items
7. Smarty template variables are assigned.
8. `xpages_page.tpl` renders the output.

That is a useful mental model when debugging or extending the module.

## 33. If you want to build something similar

Here is the recommended build order:

1. Define the data model.
2. Create `XoopsObject` classes and handlers.
3. Register the module in `xoops_version.php`.
4. Build public list and detail pages.
5. Build admin list and edit controllers.
6. Add template utilities to shape output data.
7. Add upload, URL, and file safety helpers.
8. Add blocks, search, and comments.
9. Add install/update/uninstall hooks.
10. Add tests for handlers and utility functions.

## 34. What you need to understand before extending it

To extend `xPages` safely, understand these topics first:

- XOOPS module registration through `xoops_version.php`
- `XoopsObject` and `XoopsPersistableObjectHandler`
- `Criteria` and `CriteriaCompo`
- XOOPS templates and Smarty assignment flow
- XOOPS admin bootstrap and module admin checks
- file upload validation and cleanup
- how field definitions and field values relate

If you skip these fundamentals, you will likely create inconsistencies between:

- admin forms
- database structure
- template rendering
- file cleanup

## 35. Good extension ideas

Once you understand the module, reasonable next features would be:

- page revision history
- scheduled publishing
- richer block options
- per-page permissions
- multilingual page relations
- page import/export
- stronger SEO tools
- gallery captions or alt-text improvements
- content duplication or page cloning

## 36. Reusable lessons from xPages

Even if you do not extend this exact module, these parts are worth reusing as patterns:

- split helper modules instead of one large utility file
- object plus handler organization for CRUD
- pre-shaped template descriptors instead of HTML strings
- field-definition table plus field-value table pattern
- safe upload helpers
- safe URL normalization helpers
- install/update upload-directory preparation
- integration with XOOPS blocks, comments, and search

## 37. Final advice

For users:

- keep the content model simple
- create only the fields you really need
- use aliases consistently
- treat advanced code fields as an exception, not the default

For developers:

- study the page + field + field value relationship first
- trace the request path from controller to handler to template
- keep template logic simple and move shaping logic into helper functions
- build new features in the same layered style instead of adding more controller complexity

That approach will let you use `xPages` effectively and will also help you create similar XOOPS modules with less friction.

