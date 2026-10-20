<?php
/**
 * Dynamic Options plugin for Craft CMS
 *
 * A fieldtype that renders a dropdown based on twig code.
 *
 * @link      https://webdna.co.uk
 * @copyright Copyright (c) 2018 webdna
 */

namespace webdna\dynamicoptions\fields;

use Craft;
use craft\base\ElementInterface;
use craft\helpers\Cp;
use craft\helpers\Html;
use craft\helpers\Json;
use yii\db\Schema;

/**
 * @author    webdna
 * @package   DynamicOptions
 * @since     1.0.0
 */
class DynamicOptionsMultiField extends DynamicOptionsField
{
    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('dynamic-options', 'Dynamic Multi Options');
    }

    /**
     * @inheritdoc
     */
    public static function icon(): string
    {
        return 'list-check';
    }

    /**
     * @inheritdoc
     */
    public static function phpType(): string
    {
        return 'string[]';
    }

    /**
     * @inheritdoc
     */
    public static function dbType(): string
    {
        return Schema::TYPE_JSON;
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function normalizeValue(mixed $value, ?ElementInterface $element): mixed
    {
        // Content saved before Craft 5 holds the array as a JSON string
        if (is_string($value)) {
            $value = Json::decodeIfJson($value);
        }

        if ($value === null || $value === '') {
            return [];
        }

        $values = [];
        foreach ((array)$value as $item) {
            if (is_scalar($item) && $item !== '') {
                $values[] = (string)$item;
            }
        }

        return $values;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue(mixed $value, ?ElementInterface $element): mixed
    {
        return $this->normalizeValue($value, $element) ?: null;
    }

    /**
     * @inheritdoc
     */
    public function getPreviewHtml(mixed $value, ElementInterface $element): string
    {
        return Html::encode(implode(', ', $this->normalizeValue($value, $element)));
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function inputHtml(mixed $value, ?ElementInterface $element, bool $inline): string
    {
        $options = $this->options($element);

        if ($options === null) {
            return $this->optionsErrorHtml();
        }

        $values = $this->normalizeValue($value, $element);

        return Cp::selectizeHtml([
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'class' => 'selectize',
            'name' => $this->handle,
            'values' => $values,
            'options' => $this->withStoredValues($options, $values),
            'multi' => true,
        ]);
    }
}
