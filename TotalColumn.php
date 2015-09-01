<?php

namespace bupy7\grid;

use Closure;
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
     * function($data, $models) {
     *      return !empty($data) ? count($data) / array_sum($data) : null;
     * }
     * ~~~
     */
    public $footer = self::FORMULA_SUM;
    
    private $_data = [];   
    private $_models = [];
    
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
                $this->_data[] = ArrayHelper::getValue($model, $this->realValue);
            } else {
                $this->_data[] = call_user_func($this->realValue, $model, $key, $index, $this);
            }
        } else {
            $this->_data[] = parent::getDataCellValue($model, $key, $index);
        }
        $this->_models[] = $model;
        return parent::getDataCellValue($model, $key, $index);
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
            $result = call_user_func($this->footer, $this->_data, $this->_models);
        } else {
            switch ($formula) {
                case self::FORMULA_SUM:
                    $result = array_sum($this->_data);
                    break;

                case self::FORMULA_COUNT:
                    $result = count($this->_data);
                    break;

                case self::FORMULA_AVG:
                    $result = count($this->_data) > 0 ? array_sum($this->_data) / count($$this->_data) : null;
                    break;

                case self::FORMULA_MAX:
                    $result = max($this->_data);
                    break;

                case self::FORMULA_MIN:
                    $result = min($this->_data);
                    break;

                default:
                    $result = null;
            }
        }
        return $result;
    }  
}

