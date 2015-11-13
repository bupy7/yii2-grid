<?php

namespace bupy7\grid\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use bupy7\grid\interfaces\ManagerInterface;
use yii\helpers\Url;

/**
 * Saved settings visible columns of the grid.
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.0.0
 */
class VisibleColumnsAction extends Action
{    
    /**
     * @var mixed Uniqal ID of grid. You can uses not only string, but also other types of variable.
     * Example:
     * ~~~
     * 'main-grid'
     * ~~~
     */
    public $gridId;
    /**
     * @var array|string|ManagerInterface the grid settings used for set/get actual visible columns of $gridId.
     */
    public $gridManager = 'gridManager';
    /**
     * @var mixed URL of redirect. If this property not set, will be used goBack().
     */
    public $redirectUrl;
    
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty($this->gridId) || empty($this->gridManager)) {
            throw new InvalidConfigException('Property "gridId" and "gridManager" must be specified.');
        }
        $this->gridManager = Instance::ensure($this->gridManager, 'bupy7\grid\interfaces\ManagerInterface');
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
     * Save settings of visible columns to session of user.
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