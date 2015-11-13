<?php

namespace bupy7\grid\components;

/**
 * Manager of grid.
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.1.0
 */
class Manager extends BaseManager
{
    /**
     * @inheritdoc
     */
    public function setVisibleColumns($gridId, $columns)
    {
        $this->storage->set($this->getStorageKey($gridId), serialize($columns));
    }
    
    /**
     * @inheritdoc
     */
    public function getVisibleColumns($gridId)
    {
        $key = $this->getStorageKey($gridId);
        if ($this->storage->has($key)) {
            $columns = @unserialize($this->storage->get($key));
            if (!is_array($columns)) {
                return false;
            }
            return $columns;
        }
        return false;
    }
}
