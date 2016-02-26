<?php

namespace bupy7\grid\components;

/**
 * Grid manager.
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
        $key = $this->getStorageKey([$gridId, 'visible-columns']);
        $this->storage->set($key, serialize($columns));
    }
    
    /**
     * @inheritdoc
     */
    public function getVisibleColumns($gridId)
    {
        $key = $this->getStorageKey([$gridId, 'visible-columns']);
        if ($this->storage->has($key)) {
            $columns = @unserialize($this->storage->get($key));
            if (!is_array($columns)) {
                return false;
            }
            return $columns;
        }
        return false;
    }
    
    /**
     * @inheritdoc
     */
    public function setResizableColumns($gridId, $columns)
    {
        $key = $this->getStorageKey([$gridId, 'resizable-columns']);
        $this->storage->set($key, serialize($columns));
    }
    
    /**
     * @inheritdoc
     */
    public function getResizableColumns($gridId)
    {
        $key = $this->getStorageKey([$gridId, 'resizable-columns']);
        if ($this->storage->has($key)) {
            $columns = @unserialize($this->storage->get($key));
            if (!is_array($columns)) {
                return [];
            }
            return $columns;
        }
        return [];
    }
    
    /**
     * @inheritdoc
     */
    public function setDefaultPageSize($gridId, $pageSize)
    {
        $key = $this->getStorageKey([$gridId, 'default-page-size']);
        $this->storage->set($key, serialize($pageSize));        
    }
    
    /**
     * @inheritdoc
     */
    public function getDefaultPageSize($gridId)
    {
        $key = $this->getStorageKey([$gridId, 'default-page-size']);
        if ($this->storage->has($key)) {
            return @unserialize($this->storage->get($key));
        }
        return false;
    }
}
