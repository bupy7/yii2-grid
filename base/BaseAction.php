<?php

namespace bupy7\grid\base;

use Yii;
use yii\base\Action;
use bupy7\grid\interfaces\ManagerInterface;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Base class of all actions for it grid.
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.1.3
 */
class BaseAction extends Action
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
     * @since 1.1.0
     */
    public $gridManager = 'gridManager';
    
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
    }
}
