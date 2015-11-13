<?php

namespace bupy7\grid\interfaces;

/**
 * Storage interface for working with data of grid.
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.1.0
 */
interface StorageInterface
{
    /**
     * Adds a storage data.
     * If the specified name already exists, the old value will be overwritten.
     * @param string $key Storage data name.
     * @param mixed $value Storage data value.
     */
    public function set($key, $value);
    
    /**
     * Returns the storage data value with the storage data name.
     * If the storage data does not exist, the `$defaultValue` will be returned.
     * @param string $key The storage data name.
     * @param mixed $defaultValue The default value to be returned when the storage data does not exist.
     * @return mixed The storage data value, or $defaultValue if the storage data does not exist.
     */
    public function get($key, $defaultValue = null);
    
    /**
     * @param mixed $key Storage data name.
     * @return boolean Whether the name storage data is exists.
     */
    public function has($key);
    
    /**
     * Removes a storage data.
     * @param string $key The name of the storage data to be removed.
     * @return mixed The removed value, null if no such storage data.
     */
    public function remove($key);
}