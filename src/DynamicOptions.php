<?php
/**
 * Dynamic Options plugin for Craft CMS 3.x
 *
 * A fieldtype that renders a dropdown based on twig code.
 *
 * @link      https://webdna.co.uk
 * @copyright Copyright (c) 2018 webdna
 */

namespace webdna\dynamicoptions;

use webdna\dynamicoptions\fields\DynamicOptionsField as DynamicOptionsFieldField;
use webdna\dynamicoptions\fields\DynamicOptionsMultiField as DynamicOptionsMultiFieldField;


use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;

use yii\base\Event;

/**
 * Class DynamicOptions
 *
 * @author    webdna
 * @package   DynamicOptions
 * @since     1.0.0
 *
 */
class DynamicOptions extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var DynamicOptions
     */
    public static Plugin $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = DynamicOptionsFieldField::class;
                $event->types[] = DynamicOptionsMultiFieldField::class;
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'dynamic-options',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
