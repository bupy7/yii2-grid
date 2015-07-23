<?php

namespace bupy7\grid\components;

use Yii;
use yii\base\Component;

/**
 * Management of grid settings.
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.0.0
 */
class GridSettings extends Component
{
    /**
     * Save visible columns settings.
     * @param mixed $gridId ID of grid.
     * @param array $columns List of columns.
     */
    public function setVisibleColumns($gridId, $columns)
    {
        Yii::$app->session->set(md5(serialize($gridId)), serialize($columns));
    }
    
    /**
     * Returned visible columns of grid.
     * @param mixed $gridId ID of grid.
     * @return false|array
     */
    public function getVisibleColumns($gridId)
    {
        $gridId = md5(serialize($gridId));
        if (Yii::$app->session->has($gridId)) {
            $columns = @unserialize(Yii::$app->session->get($gridId));
            if (!is_array($columns)) {
                return false;
            }
            return $columns;
        }
        return false;
    }
}