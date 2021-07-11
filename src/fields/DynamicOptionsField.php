<?php
/**
 * Dynamic Options plugin for Craft CMS 3.x
 *
 * A fieldtype that renders a dropdown based on twig code.
 *
 * @link      https://kurious.agency
 * @copyright Copyright (c) 2018 Kurious Agency
 */

namespace kuriousagency\dynamicoptions\fields;

use kuriousagency\dynamicoptions\DynamicOptions;
use kuriousagency\dynamicoptions\assetbundles\dynamicoptionsfieldfield\DynamicOptionsFieldFieldAsset;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * @author    Kurious Agency
 * @package   DynamicOptions
 * @since     1.0.0
 */
class DynamicOptionsField extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $json = '';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('dynamic-options', 'Dynamic Options');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
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
    public function getContentColumnType(): string
    {
        return Schema::TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
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

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Register our asset bundle
        //Craft::$app->getView()->registerAssetBundle(DynamicOptionsFieldFieldAsset::class);

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
		$namespacedId = Craft::$app->getView()->namespaceInputId($id);
		
		$oldMode = Craft::$app->getView()->getTemplateMode();
		Craft::$app->getView()->setTemplateMode('site');

		$variables = [];
		/**foreach(Craft::$app->globals->getAllSets() as $globalSet)
		{
			$variables[$globalSet->handle] = $globalSet;
		}*/
		$variables['element'] = $element;
		//$variables['model'] = $this->model;

		$options = Json::decode(Craft::$app->getView()->renderString($this->json, $variables), true);
		Craft::$app->getView()->setTemplateMode($oldMode);

        // Variables to pass down to our field JavaScript to let it namespace properly
        $jsonVars = [
            'id' => $id,
            'name' => $this->handle,
            'namespace' => $namespacedId,
            'prefix' => Craft::$app->getView()->namespaceInputId(''),
            ];
        $jsonVars = Json::encode($jsonVars);
       // Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').DynamicOptionsDynamicOptionsField(" . $jsonVars . ");");

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
			//'dynamic-options/_components/fields/DynamicOptionsField_input',
			'_includes/forms/select',
            [
                'name' => $this->handle,
                'value' => $value,
				'field' => $this,
				'options' => $options,
                'id' => $id,
                'namespacedId' => $namespacedId,
            ]
        );
    }
}
