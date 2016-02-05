<?php

namespace bupy7\grid\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * GridView assets files.
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.1.3
 */
class GridViewAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bupy7/grid/resources';
    public $js = [
        'js/resizable-columns.min.js',
    ];
    /**
     * @inheritdoc
     */
    public $css = [
        'css/resizable-columns.min.css',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\grid\GridViewAsset',
    ];
}