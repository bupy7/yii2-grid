<?php

namespace bupy7\grid\assets;

use yii\web\AssetBundle;

/**
 * Assets of `Sortable` plugin.
 * 
 * @author Vasilij Belosludcev <bupy765@gmail.com>
 * @see https://github.com/RubaXa/Sortable
 * @since 1.1.1
 */
class SortableAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@npm/sortablejs';
    /**
     * @inheritdoc
     */
    public $js = [
        'Sortable.min.js',
        'jquery.binding.js',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
