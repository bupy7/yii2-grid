<?php

namespace bupy7\grid\components;

use yii\base\Component;
use bupy7\grid\interfaces\ManagerInterface;
use yii\di\Instance;
use bupy7\grid\interfaces\StorageInterface;

/**
 * Manager class of grid which that implement of ManagerInterface.
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.1.0
 */
abstract class BaseManager extends Component implements ManagerInterface
{
    /**
     * @var string|array|StorageInterface A place to store the setting and follow-up with this.
     * You can pointer name of component, configuration array or instance of which implement from StorageInterface.
     */
    public $storage;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->storage = Instance::ensure($this->storage, 'bupy7\grid\interfaces\StorageInterface');
    }
    
    /**
     * Generation and returned storage key for indentified the data.
     * @param mixed $name Name of storage data.
     * @return mixed
     */
    public function getStorageKey($name)
    {
        return md5(serialize([__CLASS__, $name]));
    }
}

