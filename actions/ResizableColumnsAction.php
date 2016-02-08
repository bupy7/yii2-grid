<?php

namespace bupy7\grid\actions;

use Yii;
use bupy7\grid\base\BaseAction;

/**
 * Action saving width of resizable columns.
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.1.3
 */
class ResizableColumnsAction extends BaseAction
{
    /**
     * Saving settings of resizable column.
     * @return mixed
     */
    public function run()
    {
        $bodyParams = Yii::$app->request->getBodyParams();
        $resizableColumns = $this->gridManager->getResizableColumns($this->gridId);
        foreach ($bodyParams as $attribute => $width) {
            if (is_string($attribute)) {
                $resizableColumns[$attribute] = $width;
            }
        }
        $this->gridManager->setResizableColumns($this->gridId, $resizableColumns);
    }
}