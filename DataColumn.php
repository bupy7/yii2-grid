<?php

namespace bupy7\grid;

/**
 * @inheritdoc
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.0.1
 */
class DataColumn extends \yii\grid\DataColumn
{
    /**
     * @var boolean Whether set this property `false` then footer column will not added.
     * @see renderFooterCell()
     */
    public $footerVisible = true;

    /**
     * @inheritdoc
     */
    public function renderFooterCell()
    {
        if ($this->footerVisible) {
            return parent::renderFooterCell();
        }
    }
}
