<?php

namespace bupy7\grid\actions;

use Yii;
use bupy7\grid\base\BaseAction;
use yii\helpers\Url;

/**
 * Saved settings visible columns of the grid.
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.0.0
 */
class VisibleColumnsAction extends BaseAction
{    
    /**
     * @var mixed URL of redirect. If this property not set, will be used goBack().
     */
    public $redirectUrl;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!isset($this->redirectUrl)) {
            $this->redirectUrl = Url::previous();
        }
    }
    
    /**
     * Saving settings of visible columns. If body params is invalid - redirect.
     * @return mixed
     */
    public function run()
    {
        $params = $this->getBodyParams();
        if (empty($params)) {
            return $this->controller->redirect($this->redirectUrl);
        }
        $this->saveSettings($params);
        return $this->controller->redirect($this->redirectUrl);
    }
    
    /**
     * Save settings of visible columns to storage of user.
     * @param array $params Body params of request.
     */
    protected function saveSettings($params)
    {
        $visibleColumns = [];
        foreach ($params['columns'] as $column) {
            if (is_string($column)) {
                $visibleColumns[] = $column;
            }
        }
        $this->gridManager->setVisibleColumns($this->gridId, $visibleColumns);
    }
    
    /**
     * Returned body params of request.
     * @return array
     */
    protected function getBodyParams()
    {
        $params = Yii::$app->request->getBodyParams();
        if (empty($params['columns'])) {
            return [];
        }
        return $params;
    }
}