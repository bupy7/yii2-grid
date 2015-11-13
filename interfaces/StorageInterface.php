<?php

namespace bupy7\grid\interfaces;

/**
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.1.0
 */
interface StorageInterface
{
    public function set($key, $value);
    
    public function get($key, $defaultValue = null);
    
    public function has($key);
}