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
use craft\base\Field;
use craft\helpers\Cp;
use craft\helpers\Html;
use craft\helpers\Json;
use craft\web\View;
use yii\db\Schema;

/**
 * @author    webdna
 * @package   DynamicOptions
 * @since     1.0.0
 */
class DynamicOptionsField extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string Twig that outputs the options as comma-separated JSON objects
     */
    public string $json = '';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('dynamic-options', 'Dynamic Options');
    }

    /**
     * @inheritdoc
     */
    public static function icon(): string
    {
        return 'caret-down';
    }

    /**
     * @inheritdoc
     */
    public static function phpType(): string
    {
        return 'string|null';
    }

    /**
     * @inheritdoc
     */
    public static function dbType(): string
    {
        return Schema::TYPE_STRING;
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['json', 'string'],
        ]);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue(mixed $value, ?ElementInterface $element): mixed
    {
        if ($value === null || $value === '' || !is_scalar($value)) {
            return null;
        }

        return (string)$value;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'dynamic-options/_components/fields/DynamicOptionsField_settings',
            [
                'field' => $this,
                'id' => 'json',
                'name' => 'json',
            ]
        );
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

        $value = $this->normalizeValue($value, $element);

        return Cp::selectHtml([
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $value,
            'options' => $this->withStoredValues($options, $value === null ? [] : [$value]),
        ]);
    }

    /**
     * Renders the field’s Twig in site mode and decodes it into select options.
     *
     * Returns null (and logs why) when the Twig throws or does not produce valid JSON.
     */
    protected function options(?ElementInterface $element): ?array
    {
        try {
            $output = Craft::$app->getView()->renderString($this->json, ['element' => $element], View::TEMPLATE_MODE_SITE);
            $options = Json::decode('[' . $output . ']');
        } catch (\Throwable $e) {
            Craft::warning("The options for field “{$this->handle}” could not be built: {$e->getMessage()}", __METHOD__);
            return null;
        }

        return is_array($options) ? $options : null;
    }

    /**
     * Adds any stored value missing from the options, so saving the element does not silently change or drop it.
     */
    protected function withStoredValues(array $options, array $values): array
    {
        $known = [];
        foreach ($options as $option) {
            if (is_array($option) && isset($option['value'])) {
                $known[] = (string)$option['value'];
            }
        }

        foreach ($values as $value) {
            if (!in_array((string)$value, $known, true)) {
                $options[] = ['label' => (string)$value, 'value' => (string)$value];
            }
        }

        return $options;
    }

    /**
     * Returns the message shown in place of the input when the options could not be built.
     */
    protected function optionsErrorHtml(): string
    {
        return Html::tag('p', Craft::t('dynamic-options', 'This field’s options could not be built. Check that its Twig outputs valid JSON.'), [
            'class' => 'error',
        ]);
    }
}
