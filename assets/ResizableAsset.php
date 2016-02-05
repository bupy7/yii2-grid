<?php

namespace bupy7\grid\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Assets of jQuery plugin `resizable`.
 * Home page: https://github.com/tannernetwork/resizable
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.1.3
 */
class ResizableAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@npm/jquery-resizable';
    /**
     * @inheritdoc
     */
    public $js = [
        'resizable.js',
    ];
    /**
     * @inheritdoc
     */
    public $css = [
        'resizable.css',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}

