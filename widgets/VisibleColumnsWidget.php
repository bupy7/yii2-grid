<?php

namespace bupy7\grid\widgets;

use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use bupy7\grid\components\GridSettings;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/**
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.0.0
 */
class VisibleColumnsWidget extends Widget
{
    /**
     * @var mixed
     */
    public $gridId;
    /**
     * @var mixed
     */
    public $gridSettings = 'gridSettings';
    /**
     * @var array
     */
    public $modalOptions = [];
    /**
     * @var string|array
     */
    public $actionForm = '';
    /**
     * @var string
     */
    public $methodForm = 'post';
    /**
     * @var array
     */
    public $formOptions = [];
    /**
     * @var array
     */
    public $columnsList = [];
    /**
     * @var string
     */
    public $submitBtnLabel = 'Apply';
    /**
     * @var array
     */
    public $submitBtnOptions = ['class' => 'btn btn-primary'];
    
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty($this->gridId) || empty($this->gridSettings)) {
            throw new InvalidConfigException('Property "gridId" and "gridSettings" must be specified.');
        }
        $this->gridSettings = Instance::ensure($this->gridSettings, GridSettings::className());
    }
    
    /**
     * Display modal window with form for selecting visible columns.
     */
    public function run()
    {
        $visibleColumns = $this->gridSettings->getVisibleColumns($this->gridId);
        if ($visibleColumns === false) {
            $visibleColumns = array_keys($this->columnsList);
        }       
        Modal::begin($this->modalOptions);
        echo Html::beginForm($this->actionForm, $this->methodForm, $this->formOptions);
        echo Html::checkboxList('columns', $visibleColumns, $this->columnsList);
        echo Html::beginTag('div', ['class' => 'form-group']);
        echo Html::submitButton($this->submitBtnLabel, $this->submitBtnOptions);
        echo Html::endTag('div');
        echo Html::endForm();
        Modal::end();
    }
}

