<?php

namespace bupy7\grid;

use Closure;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

/**
 * Total column. Adding to last row total formula for all items from this column.
 * 
 * @author Belosludcev Vasilij http://mihaly4.ru
 * @since 1.0.0
 */
class TotalColumn extends DataColumn
{
    /**
     * Formula: summary.
     */
    const FORMULA_SUM = 1;
    /**
     * Formula: count.
     */
    const FORMULA_COUNT = 2;
    /**
     * Formula: average value.
     */
    const FORMULA_AVG = 3;
    /**
     * Formula: maxmimum.
     */
    const FORMULA_MAX = 4;
    /**
     * Formula: minimum.
     */
    const FORMULA_MIN = 5;
    
    /**
     * @var string|\Closure an anonymous function or a string that is used to calculate formula of the column.
     * If not set, then will be uses value from [[$value]] property. 
     * @see $value
     */
    public $realValue;
    /**
     * @var int|\Closure an anonymous function or `FORMULA_*` constant which will calculate the output of the content.
     * ~~~
     * TotalCount::FORMULA_MAX
     * ~~~
     * 
     * or
     * ~~~
     * function($data) {
     *      return !empty($data) ? count($data) / array_sum($data) : null;
     * }
     * ~~~
     */
    public $footer = self::FORMULA_SUM;
    
    private $_data = [];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->grid->showFooter = true;
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    public function getDataCellValue($model, $key, $index)
    {
        if (isset($this->realValue)) {
            if (is_string($this->realValue)) {
                $value = ArrayHelper::getValue($model, $this->realValue);
            } else {
                $value = call_user_func($this->realValue, $model, $key, $index, $this);
            }
        } else {
            $value = parent::getDataCellValue($model, $key, $index);
        }
        $this->_data[] = $value;
        return $value;
    }
    
    /**
     * @inheritdoc
     */
    protected function renderFooterCellContent()
    {
        $footer = $this->calculateSummary();
        return trim($footer) !== '' ? $footer : $this->grid->emptyCell;
    }
    
    /**
     * Calculates the summary of an input data based on page summary aggregration function.
     * @return mixed
     */
    protected function calculateSummary()
    {
        if (empty($this->_data)) {
            return '';
        }
        $formula = $this->footer;
        if ($formula instanceof Closure) {
            return call_user_func($this->footer, $this->_data);
        }
        switch ($formula) {
            case self::FORMULA_SUM:
                return array_sum($this->_data);
            case self::FORMULA_COUNT:
                return count($this->_data);
            case self::FORMULA_AVG:
                return count($this->_data) > 0 ? array_sum($this->_data) / count($$this->_data) : null;
            case self::FORMULA_MAX:
                return max($this->_data);
            case self::FORMULA_MIN:
                return min($this->_data);
        }
        return null;
    }  
}

