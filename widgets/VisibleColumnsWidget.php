<?php

namespace bupy7\grid\widgets;

use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use bupy7\grid\interfaces\ManagerInterface;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/**
 * Generation of modal window with list of available columns for selecting need to visible.
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @since 1.0.0
 */
class VisibleColumnsWidget extends Widget
{
    /**
     * @var mixed Uniqal ID of grid. You can uses not only string, but also other types of variable.
     * Example:
     * ~~~
     * 'main-grid'
     * ~~~
     */
    public $gridId;
    /**
     * @var array|string|ManagerInterface the grid settings used for set/get actual visible columns of $gridId.
     * @since 1.1.0
     */
    public $gridManager = 'gridManager';
    /**
     * @var array Modal window widget options.
     * @see \yii\bootstrap\Modal
     */
    public $modalOptions = [];
    /**
     * @var string|array Action URL of form.
     * @see Html::beginForm()
     */
    public $actionForm = '';
    /**
     * @var string Method of form.
     * @see Html::beginForm()
     */
    public $methodForm = 'post';
    /**
     * @var array Options of form.
     * @see Html::beginForm()
     */
    public $formOptions = [];
    /**
     * @var array List of available columns in grid with labels:
     * Example:
     * [
     *      'attribute1' => 'Label of attribute1',
     *      'attribute2' => 'Label of attribute2',
     * ]
     */
    public $columnsList = [];
    /**
     * @var string Label of submit button.
     * @see Html::submitButton()
     */
    public $submitBtnLabel = 'Apply';
    /**
     * @var array Options of submit button.
     * @see Html::submitButton()
     */
    public $submitBtnOptions = ['class' => 'btn btn-primary'];
    
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty($this->gridId) || empty($this->gridManager)) {
            throw new InvalidConfigException('Property "gridId" and "gridManager" must be specified.');
        }
        $this->gridManager = Instance::ensure($this->gridManager, 'bupy7\grid\interfaces\ManagerInterface');
    }
    
    /**
     * Display modal window with form for selecting visible columns.
     */
    public function run()
    {
        $visibleColumns = $this->gridManager->getVisibleColumns($this->gridId);
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

