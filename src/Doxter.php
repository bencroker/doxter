<?php
namespace selvinortiz\doxter;

use yii\base\Event;

use Craft;
use craft\base\Plugin;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;

use selvinortiz\doxter\fields\DoxterField;
use selvinortiz\doxter\models\SettingsModel;
use selvinortiz\doxter\services\DoxterService;
use selvinortiz\doxter\variables\DoxterVariable;
use selvinortiz\doxter\extensions\DoxterExtension;
use selvinortiz\doxter\assetbundles\doxterplugin\DoxterPluginBundle;

/**
 * Class Doxter
 *
 * @package selvinortiz\doxter;
 *
 * @property DoxterService $api
 */
class Doxter extends Plugin
{
    public function init()
    {
        parent::init();

        Craft::$app->view->registerTwigExtension(new DoxterExtension());

        Event::on(
            Fields::className(),
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function(RegisterComponentTypesEvent $event)
            {
                $event->types[] = DoxterField::class;
            }
        );

        $this->name          = $this->getSettings()->pluginAlias;
        $this->hasCpSection  = $this->getSettings()->enableCpTab;
        $this->hasCpSettings = true;
    }

    /**
     * @return SettingsModel
     */
    public function createSettingsModel()
    {
        return new SettingsModel();
    }

    /**
     * @return string
     */
    protected function settingsHtml()
    {
        $settings  = $this->getSettings();
        $variables = [
            'plugin'   => $this,
            'settings' => $settings,
        ];

        Craft::$app->getView()->registerAssetBundle(DoxterPluginBundle::class);

        return Craft::$app->getView()->renderTemplate('doxter/_settings', $variables);
    }

    /**
     * @return string
     */
    public function defineTemplateComponent()
    {
        return DoxterVariable::class;
    }

    public function registerShortcodes()
    {
        return [
            'image'         => 'selvinortiz\\doxter\\common\\shortcodes\\DoxterShortcodes@image',
            'audio'         => 'selvinortiz\\doxter\\common\\shortcodes\\DoxterShortcodes@audio',
            'updates'       => 'selvinortiz\\doxter\\common\\shortcodes\\DoxterShortcodes@updates',
            'vimeo:youtube' => 'selvinortiz\\doxter\\common\\shortcodes\\DoxterShortcodes@video',
        ];
    }
}

/**
 * Allows me to use a more expressive syntax and have more control over type hints
 *
 * @return Doxter
 */
function doxter(): Doxter
{
    static $instance;

    if (null === $instance)
    {
        $instance = Doxter::getInstance();
    }

    return $instance;
}
