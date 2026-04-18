<?php

declare(strict_types=1);

namespace XoopsModules\Xpages;

/**
 * xPages — Module helper.
 *
 * Subclass of Xmf\Module\Helper with a getHandler() override that
 * looks up namespaced handler classes (XoopsModules\Xpages\PageHandler,
 * FieldHandler, etc.) instead of the parent's
 * class/{name}.php + Xpages{Name}Handler global-namespace convention.
 *
 * Current callers:
 *   - include/functions.php::xpages_get_handler() delegates here.
 *   - include/functions.php::xpages_load_language() delegates here.
 *
 * @package xpages
 */
class Helper extends \Xmf\Module\Helper
{
    public function __construct()
    {
        parent::__construct(basename(dirname(__DIR__)));
    }

    /**
     * Process-wide singleton.
     */
    public static function getInstance(): self
    {
        static $instance;
        if (null === $instance) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Return the dirname string.
     */
    public function getDirname(): string
    {
        return (string)$this->dirname;
    }

    /**
     * Resolve a module handler by name.
     *
     * Overrides the parent's class/{name}.php convention in favour of
     * PSR-4 namespaced lookup under XoopsModules\Xpages\*. Example:
     *   getHandler('page') → \XoopsModules\Xpages\PageHandler
     *
     * The preloads/autoloader (registered at XOOPS bootstrap) picks up
     * the class/*.php file; we instantiate it with the shared DB
     * connection and cache the instance per request.
     *
     * @param string $name handler short name (e.g. 'page', 'field')
     * @return \XoopsObjectHandler|\XoopsPersistableObjectHandler|false
     *     handler instance, or false if the class is not defined
     */
    public function getHandler($name)
    {
        $key   = strtolower((string)$name);
        $class = __NAMESPACE__ . '\\' . ucfirst($key) . 'Handler';

        if (!class_exists($class)) {
            $this->addLog("ERROR :: Handler class '{$class}' not found");
            return false;
        }

        if (!isset($this->handlers[$key])) {
            $db = \XoopsDatabaseFactory::getDatabaseConnection();
            $this->handlers[$key] = new $class($db);
            $this->addLog("Loading handler '{$class}'");
        }

        return $this->handlers[$key];
    }
}
