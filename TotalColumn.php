<?php

namespace bupy7\grid;

use Closure;
use yii\grid\DataColumn;

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
     * @var int|Closure
     */
    public $formula = self::FORMULA_SUM;
    
    private $_rows = [];
    
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
        $value = parent::getDataCellValue($model, $key, $index);
        $this->_rows[] = $value;
        return $value;
    }
    
    /**
     * @inheritdoc
     */
    protected function renderFooterCellContent()
    {
        if ($this->footer !== null) {
            $footer = $this->footer;
        } else {
            $footer = $this->calculateSummary();
        }
        return trim($footer) !== '' ? $footer : $this->grid->emptyCell;
    }
    
    /**
     * Calculates the summary of an input data based on page summary aggregration function.
     * @return mixed
     */
    protected function calculateSummary()
    {
        if (empty($this->_rows)) {
            return '';
        }
        $formula = $this->formula;
        if ($formula instanceof Closure) {
            return call_user_func($this->formula, $this->_rows);
        }
        switch ($formula) {
            case self::FORMULA_SUM:
                return array_sum($this->_rows);
            case self::FORMULA_COUNT:
                return count($this->_rows);
            case self::FORMULA_AVG:
                return count($this->_rows) > 0 ? array_sum($this->_rows) / count($$this->_rows) : null;
            case self::FORMULA_MAX:
                return max($this->_rows);
            case self::FORMULA_MIN:
                return min($this->_rows);
        }
        return null;
    }
    
}

