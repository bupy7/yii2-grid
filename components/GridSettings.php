<?php

namespace bupy7\grid\components;

use Yii;
use yii\base\Component;

/**
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.0.0
 */
class GridSettings extends Component
{
    /**
     * 
     * @param type $gridId
     * @param type $columns
     */
    public function setVisibleColumns($gridId, $columns)
    {
        Yii::$app->session->set(md5(serialize($gridId)), serialize($columns));
    }
    
    /**
     * 
     * @param type $gridId
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