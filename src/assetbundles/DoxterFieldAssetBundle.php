<?php
namespace selvinortiz\doxter\assetbundles;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class DoxterFieldAssetBundle extends AssetBundle {

    public function init() {
        $this->sourcePath = '@selvinortiz/doxter/assetbundles/field';
        $this->depends    = [CpAsset::class];

        $this->js = [
            'js/simplemde.js',
            'js/plugin.js',
        ];

        $this->css = [
            'css/simplemde.css',
            'css/plugin.css',
        ];

        parent::init();
    }
}
