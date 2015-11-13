<?php

namespace bupy7\grid\components;

use yii\base\Component;
use bupy7\grid\interfaces\ManagerInterface;
use yii\di\Instance;
use bupy7\grid\interfaces\StorageInterface;

/**
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.1.0
 */
abstract class BaseManager extends Component implements ManagerInterface
{
    /**
     * @var string|array|StorageInterface
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
     * Generation and returned storage key.
     * @param string $name Name of key.
     * @return string
     */
    protected function getStorageKey($name)
    {
        return md5(serialize([__CLASS__, $name]));
    }
}

