<?php
/**
 * Dynamic Options plugin for Craft CMS 3.x
 *
 * A fieldtype that renders a dropdown based on twig code.
 *
 * @link      https://webdna.co.uk
 * @copyright Copyright (c) 2018 webdna
 */

namespace webdna\dynamicoptions\assetbundles\dynamicoptionsfieldfield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    webdna
 * @package   DynamicOptions
 * @since     1.0.0
 */
class DynamicOptionsFieldFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        $this->sourcePath = "@webdna/dynamicoptions/assetbundles/dynamicoptionsfieldfield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/DynamicOptionsField.js',
        ];

        $this->css = [
            'css/DynamicOptionsField.css',
        ];

        parent::init();
    }
}
