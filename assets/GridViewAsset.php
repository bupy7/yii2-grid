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
    public $js = [];
    /**
     * @inheritdoc
     */
    public $css = [];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\grid\GridViewAsset',
    ];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->css[] = 'css/resizable-columns' . (!YII_DEBUG ? '.min' : '') . '.css';
        $this->js[] = 'js/resizable-columns' . (!YII_DEBUG ? '.min' : '') . '.js';
    }
}