<?php

namespace bupy7\grid\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use bupy7\grid\components\GridSettings;

/**
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.0.0
 */
class VisibleColumnsAction extends Action
{    
    /**
     * @var mixed
     */
    public $gridId;
    /**
     * @var mixed
     */
    public $gridSettings = 'gridSettings';
    
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty($this->gridId) || empty($this->gridSettings)) {
            throw new InvalidConfigException('Property "gridId" and "gridSettings" must be specified.');
        }
        $this->gridSettings = Instance::ensure($this->gridSettings, GridSettings::className());
    }
    
    /**
     * 
     * @return mixed
     */
    public function run()
    {
        $params = $this->getBodyParams();
        if (empty($params)) {
            return $this->controller->goBack();
        }
        $this->saveSettings($params);
        return $this->controller->goBack();
    }
    
    /**
     * @param array $params
     */
    protected function saveSettings($params)
    {
        $visibleColumns = [];
        foreach ($params['columns'] as $column) {
            if (is_string($column)) {
                $visibleColumns[] = $column;
            }
        }
        $this->gridSettings->setVisibleColumns($this->gridId, $visibleColumns);
    }
    
    /**
     * 
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