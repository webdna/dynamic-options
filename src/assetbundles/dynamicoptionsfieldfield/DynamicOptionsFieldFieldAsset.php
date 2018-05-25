<?php
/**
 * Dynamic Options plugin for Craft CMS 3.x
 *
 * A fieldtype that renders a dropdown based on twig code.
 *
 * @link      https://kurious.agency
 * @copyright Copyright (c) 2018 Kurious Agency
 */

namespace kuriousagency\dynamicoptions\assetbundles\dynamicoptionsfieldfield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Kurious Agency
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
    public function init()
    {
        $this->sourcePath = "@kuriousagency/dynamicoptions/assetbundles/dynamicoptionsfieldfield/dist";

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
