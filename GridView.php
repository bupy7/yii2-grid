<?php

namespace bupy7\grid;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\DataColumn;
use yii\widgets\BaseListView;
use bupy7\grid\assets\GridViewAsset;
use yii\helpers\Json;
use yii\grid\GridView as BaseGridView;
use yii\helpers\Url;
use yii\di\Instance;
use bupy7\grid\interfaces\ManagerInterface;
use yii\data\Pagination;

/**
 * Simple extended `yii\grid\GridView`.
 * 
 * @author Vasilij Belosludcev https://github.com/bupy7
 * @since 1.0.0
 */
class GridView extends BaseGridView
{
    /**
     * Type of panel `default`.
     */
    const PANEL_DEFAULT = 'panel-default';
    /**
     * Type of panel `info`.
     */
    const PANEL_INFO = 'panel-info';
    /**
     * Type of panel `success`.
     */
    const PANEL_SUCCESS = 'panel-success';
    /**
     * Type of panel `danger`.
     */
    const PANEL_DANGER = 'panel-danger';
    /**
     * Type of panel `warning`.
     */
    const PANEL_WARNING= 'panel-warning';
    /**
     * Type of panel `primary`.
     */
    const PANEL_PRIMARY = 'panel-primary';
       
    /**
     * @var string|false Type of panel. Whether set 'false' then panel don't uses.
     */
    public $panel = self::PANEL_DEFAULT;
    /**
     * @var string HTML-template for layout of panel.
     * Allows uses:
     *      - `{type}` - the type of panel. See [[renderPanel]].
     *      - `{panelHeading}` - the heading of panel. See [[renderPanel]]. 
     *      - `{panelFooter}` - the footer of panel. See [[renderPanel]].
     *      - `{items}` - the summary section. See [[renderSummary()]].
     *      - `{summary}` - the summary section. See [[renderSummary()]].
     *      - `{pager}` - the pager. See [[renderPager()]].
     *      - `{pageSizer}` - the page size. See [[renderPageSizer]].
     *      - `{errors}` - the filter model error summary. See [[renderErrors()]].
     *      - `{sorter}` - the sorter. See [[renderSorter()]].
     * @see http://getbootstrap.com/components/#panels
     */
    public $panelTemplate = <<<HTML
<div class="panel {type}">
    {panelHeading}
    <div class="panel-body">{items}</div>
    {panelFooter}
</div>
HTML;
    /**
     * @var boolean Whether set 'true' will be displays heading of panel. 
     */
    public $panelHeading = true;
    /**
     * @var string HTML-template for heading of panel.
     * Allows uses:
     *      - `{items}` - the summary section. See [[renderSummary()]].
     *      - `{summary}` - the summary section. See [[renderSummary()]].
     *      - `{pager}` - the pager. See [[renderPager()]].
     *      - `{pageSizer}` - the page size. See [[renderPageSizer]].
     *      - `{errors}` - the filter model error summary. See [[renderErrors()]].
     *      - `{sorter}` - the sorter. See [[renderSorter()]].
     * @see http://getbootstrap.com/components/#panels-heading
     */
    public $panelHeadingTemplate = <<<HTML
    <div class="col-md-12 text-right">{pageSizer}</div>
    <div class="clearfix"></div>
HTML;
    /**
     * @var boolean Whether set 'true' will be displays footer of panel.
     */
    public $panelFooter = true;
    /**
     * @var string HTML-template for footer of panel.
     * Allows uses:
     *      - `{items}` - the summary section. See [[renderSummary()]].
     *      - `{summary}` - the summary section. See [[renderSummary()]].
     *      - `{pager}` - the pager. See [[renderPager()]].
     *      - `{pageSizer}` - the page size. See [[renderPageSizer]].
     *      - `{errors}` - the filter model error summary. See [[renderErrors()]].
     *      - `{sorter}` - the sorter. See [[renderSorter()]].
     * @see http://getbootstrap.com/components/#panels-footer
     */
    public $panelFooterTemplate = <<<HTML
    <div class="row">
        <div class="col-md-6">{summary}</div>
        <div class="col-md-6 text-right">{pager}</div>
    </div>
HTML;
    /**
     * Tags to replace in the rendered layout. Enter this as `$key => $value` pairs, where:
     * - $key: string, defines the flag.
     * - $value: string|Closure, the value that will be replaced. You can set it as a callback
     *   function to return a string of the signature:
     *      function ($widget) { return 'custom'; }
     *
     * For example:
     * ['{flag}' => '<span class="glyphicon glyphicon-asterisk"></span']
     *
     * @var array
     */
    public $customTags = [];
    /**
     * @var array|string, configuration of additional header table rows that will be rendered before the default grid
     * header row. If set as a string, it will be displayed as is, without any HTML encoding. If set as an array, each
     * row in this array corresponds to a HTML table row, where you can configure the columns with these properties:
     * - columns: array, the header row columns configuration where you can set the following properties:
     *      - content: string, the table cell content for the column
     *      - tag: string, the tag for rendering the table cell. If not set, defaults to 'th'.
     *      - options: array, the HTML attributes for the table cell
     * - options: array, the HTML attributes for the table row
     */
    public $beforeHeader = [];
    /**
     * @var array|string, configuration of additional header table rows that will be rendered after default grid
     * header row. If set as a string, it will be displayed as is, without any HTML encoding. If set as an array, each
     * row in this array corresponds to a HTML table row, where you can configure the columns with these properties:
     * - columns: array, the header row columns configuration where you can set the following properties:
     *      - content: string, the table cell content for the column
     *      - tag: string, the tag for rendering the table cell. If not set, defaults to 'th'.
     *      - options: array, the HTML attributes for the table cell
     * - options: array, the HTML attributes for the table row
     */
    public $afterHeader = [];
    /**
     * @var array|string, configuration of additional footer table rows that will be rendered before the default grid
     * footer row. If set as a string, it will be displayed as is, without any HTML encoding. If set as an array, each
     * row in this array corresponds to a HTML table row, where you can configure the columns with these properties:
     * - columns: array, the footer row columns configuration where you can set the following properties:
     *      - content: string, the table cell content for the column
     *      - tag: string, the tag for rendering the table cell. If not set, defaults to 'th'.
     *      - options: array, the HTML attributes for the table cell
     * - options: array, the HTML attributes for the table row
     */
    public $beforeFooter = [];
    /**
     * @var array|string, configuration of additional footer table rows that will be rendered after the default grid
     * footer row. If set as a string, it will be displayed as is, without any HTML encoding. If set as an array, each
     * row in this array corresponds to a HTML table row, where you can configure the columns with these properties:
     * - columns: array, the footer row columns configuration where you can set the following properties:
     *      - content: string, the table cell content for the column
     *      - tag: string, the tag for rendering the table cell. If not set, defaults to 'th'.
     *      - options: array, the HTML attributes for the table cell
     * - options: array, the HTML attributes for the table row
     */
    public $afterFooter = [];
    /**
     * @var array the configuration for the page sizer widget. By default, `LinkPageSizer` will be
     * used to render the page sizer. You can use a different widget class by configuring the "class" element.
     */
    public $pageSizer = [];
    /**
     * @var boolean whether the grid table will have a `bordered` style.
     */
    public $bordered = false;
    /**
     * @var boolean whether the grid table will have a `striped` style.
     */
    public $striped = true;
    /**
     * @var boolean whether the grid table will highlight row on `hover`.
     */
    public $hover = true;
    /**
     * @inheritdoc
     */
    public $tableOptions = [];
    /**
     * @var false|array Contain list name attributes which must be displays in grid. 
     * Whether `false` will be visible all columns from `columns` list. 
     * Example:
     * ~~~
     * ['id', 'username', 'email]
     * ~~~
     */
    public $visibleColumns = false;
    /**
     * @inheritdoc
     */
    public $dataColumnClass = 'bupy7\grid\DataColumn';
    /**
     * @var false|array Contain key-value pairs width of columns where `key` is attribute and `value` is 
     * width of column at `px`. In case set as `false` then it function not used.
     * Example:
     * ~~~
     * [
     *      'id' => '200',
     *      'username' => '123',
     *      //and etc
     * ]
     * ~~~
     * @since 1.1.3
     */
    public $resizableColumns = false;
    /**
     * @var array Options of resizable columns plugin.
     * List options:
     * - `selector` (string): CSS relative path to header columns of table.  
     */
    public $resizableColumnsOptions = [
        'selector' => 'tr > th[data-resizable-column], tr > td[data-resizable-column]',
    ];
    /**
     * @var array|string URL to action for save width of resizable column after changes it.
     */
    public $resizableColumnsUrl = ['url/to/action'];
    /**
     * @var ManagerInterface|array|string
     * @since 1.1.4
     */
    public $gridManager = 'gridManager';
    /**
     * @var boolean Keeping the page sizer. If set as `true` then last modified page sizer will be keep to 
     * the storage.
     * @since 1.1.4
     */
    public $keepPageSizer = false;
    
    /**
     * @inheritdoc
     * @since 1.1.4
     */
    public function init()
    {
        parent::init();
        if ($this->keepPageSizer) {
            $this->gridManager = Instance::ensure($this->gridManager, 'bupy7\grid\interfaces\ManagerInterface');
        }
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        Html::addCssClass($this->tableOptions, 'table');
        if ($this->hover) {
            Html::addCssClass($this->tableOptions, 'table-hover');
        }
        if ($this->bordered) {
            Html::addCssClass($this->tableOptions, 'table-bordered');
        }
        if ($this->striped) {
            Html::addCssClass($this->tableOptions, 'table-striped');
        }
        $this->initLayout();
        
        GridViewAsset::register($this->view);
        
        $options = Json::htmlEncode($this->getClientOptions());
        $this->view->registerJs("$('#{$this->options['id']}').yiiGridView({$options});");
        
        if ($this->resizableColumns !== false) {
            $options = Json::htmlEncode($this->resizableColumnsOptions);
            $url = Url::toRoute($this->resizableColumnsUrl);
            $js = <<<JS
$('#{$this->options['id']}').resizableColumns({$options}).on('afterDragging.rc', function(event) {
    var column = $(this).closest('[data-resizable-column]'),
        data = {};
    data[column.data('resizable-column')] = column.outerWidth();
    $.ajax({
        type: 'post',
        url: '{$url}',
        data: data
    });
});
JS;
            $this->view->registerJs($js);
        }
        BaseListView::run();
    }
    
    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{pageSizer}':
                return $this->renderPageSizer();
            default:
                return parent::renderSection($name);
        }
    }

    /**
     * Renders the page sizer.
     * @return string the rendering result
     */
    public function renderPageSizer()
    {
        $pagination = $this->getPagination();
        if ($pagination === false) {
            return '';
        }
        /* @var $class LinkPageSizer */
        $pageSizer = $this->pageSizer;
        $class = ArrayHelper::remove($pageSizer, 'class', LinkPageSizer::className());
        $pageSizer['pagination'] = clone $pagination;
        $pageSizer['view'] = $this->getView();

        return $class::widget($pageSizer);
    }
    
    /**
     * Generation layout with panel if it uses.
     */
    public function renderPanel()
    {
        if ($this->panel === false) {
            return;
        }
        $this->layout = strtr(
            $this->panelTemplate,
            [
                '{panelHeading}' => $this->panelHeading !== false 
                    ? Html::tag('div', $this->panelHeadingTemplate, ['class' => 'panel-heading']) 
                    : '',
                '{type}' => $this->panel,
                '{panelFooter}' => $this->panelFooter !== false 
                    ? Html::tag('div', $this->panelFooterTemplate, ['class' => 'panel-footer']) 
                    : '',
            ]
        );
    }
    
    /**
     * Initialization layout of GridView.
     */
    public function initLayout()
    {
        $this->renderPanel();
        foreach ($this->customTags as $key => $value) {
            if ($value instanceof \Closure) {
                $value = call_user_func($value, $this);
            }
            $this->layout = str_replace("{{$key}}", $value, $this->layout);
        }
    }
    
    /**
     * @inheritdoc
     */
    public function renderTableHeader()
    {
        $content = parent::renderTableHeader();
        return strtr(
            $content,
            [
                '<thead>' => "<thead>\n" . $this->generateRows($this->beforeHeader),
                '</thead>' => $this->generateRows($this->afterHeader) . "\n</thead>",
            ]
        );
    }
    
    /**
     * @inheritdoc
     */
    public function renderTableFooter()
    {
        $content = parent::renderTableFooter();
        return strtr(
            $content,
            [
                '<tfoot>' => "<tfoot>\n" . $this->generateRows($this->beforeFooter),
                '</tfoot>' => $this->generateRows($this->afterFooter) . "\n</tfoot>",
            ]
        );
    }
    
    /**
     * @inheritdoc
     */
    public function initColumns()
    {
        $visibleColumns = false;
        if (is_array($this->visibleColumns)) {
            $visibleColumns = array_flip($this->visibleColumns);
        }
        if (empty($this->columns)) {
            $this->guessColumns();
        }
        $dataColumns = [];
        $serviceColumns = [];
        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                $column = Yii::createObject(array_merge([
                    'class' => $this->dataColumnClass ? : DataColumn::className(),
                    'grid' => $this,
                ], $column));
            }
            if ($column->visible) {
                $key = $i;
                if ($visibleColumns !== false) {
                    if ($column instanceof DataColumn) {
                        if (isset($visibleColumns[$column->attribute])) {
                            $key = $visibleColumns[$column->attribute];
                        } else {
                            continue;
                        }             
                    } else {
                        $serviceColumns[$i] = $column;
                        continue;
                    }
                }
                $dataColumns[$key] = $column;
            }
        }
        if ($this->resizableColumns !== false) {
            foreach ($dataColumns as $column) {
                if (!($column instanceof DataColumn)) {
                    continue;
                }
                $column->headerOptions['data-resizable-column'] = $column->attribute;
                if (isset($this->resizableColumns[$column->attribute])) { 
                    Html::addCssStyle($column->headerOptions, [
                        'width' => $this->resizableColumns[$column->attribute] . 'px',
                        'min-width' => $this->resizableColumns[$column->attribute] . 'px',
                    ]);
                }
            }
        }
        $this->columns = $this->mergeColumns($dataColumns, $serviceColumns);
    }
    
    /**
     * Sorting and ordering columns by specific criteria.
     * @param array $dataColumns Array of columns DataColumn instance.
     * @param array $serviceColumns Array of columns with other class instance.
     * @return array
     * @since 1.1.3
     */
    protected function mergeColumns(array $dataColumns, array $serviceColumns)
    {
        $columns = $dataColumns;
        ksort($columns);
        foreach ($serviceColumns as $i => $column) {
            $tmp = array_slice($columns, 0, $i);
            $tmp[] = $column;
            $columns = array_merge($tmp, array_slice($columns, $i));
        }
        return $columns;
    }
    
    /**
     * Generate HTML markup for additional table rows for header and/or footer
     * 
     * @param array|string $data the table rows configuration
     * @return string
     */
    protected function generateRows($data)
    {
        if (empty($data)) {
            return '';
        }
        if (is_string($data)) {
            return $data;
        }
        $rows = '';
        if (is_array($data)) {
            foreach ($data as $row) {
                if (empty($row['columns'])) {
                    continue;
                }
                $rowOptions = ArrayHelper::getValue($row, 'options', []);
                $rows .= Html::beginTag('tr', $rowOptions);
                foreach ($row['columns'] as $col) {
                    $colOptions = ArrayHelper::getValue($col, 'options', []);
                    $colContent = ArrayHelper::getValue($col, 'content', '');
                    $tag = ArrayHelper::getValue($col, 'tag', 'th');
                    $rows .= "\t" . Html::tag($tag, $colContent, $colOptions) . "\n";
                }
                $rows .= Html::endTag('tr') . "\n";
            }
        }
        return $rows;
    }
    
    /**
     * @return Pagination|false The pagination object. If this is false, it means the pagination is disabled.
     * @since 1.1.4
     */
    protected function getPagination()
    {
        $pagination = $this->dataProvider->getPagination();
        if ($pagination === false) {
            return false;
        }
        if ($this->keepPageSizer) {
            $defaultPageSize = $this->gridManager->getDefaultPageSize($this->getId());
            if ($defaultPageSize !== false) {
                $pagination->defaultPageSize = $defaultPageSize;
            }
            $this->gridManager->setDefaultPageSize($this->getId(), $pagination->getPageSize());
        }
        return $pagination;
    }
}
