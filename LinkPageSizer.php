<?php

namespace bupy7\grid;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\base\Widget;
use yii\data\Pagination;
use yii\web\Request;

/**
 * LinkPageSizer displays a list of hyperlinks that lead to different page sizes of a target.
 *
 * LinkPageSizer works with a [[Pagination]] object which specifies the total number
 * of pages and the current page number.
 *
 * Note that LinkPageSizer only generates the necessary HTML markups. In order for it
 * to look like a real pager, you should provide some CSS styles for it.
 * With the default configuration, LinkPageSizer should look good using Twitter Bootstrap CSS framework.
 * 
 * @author nkovacs https://github.com/nkovacs
 * @author Vasilij Belosludcev https://github.com/bupy7
 * @since 1.0.0
 */
class LinkPageSizer extends Widget
{
    /**
     * @var Pagination the pagination object that this pager is associated with.
     * You must set this property in order to make LinkPageSizer work.
     */
    public $pagination;
    /**
     * @var array available page sizes. Array keys are sizes, values are labels.
     */
    public $availableSizes = [10 => '10', 20 => '20', 40 => '40'];
    /**
     * @var array HTML attributes for the pager container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'pagination'];
    /**
     * @var array HTML attributes for the link in a pager container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $linkOptions = [];
    /**
     * @var string the CSS class for the active (currently selected) page size button.
     */
    public $activePageSizeCssClass = 'active';

    /**
     * Initializes the pager.
     */
    public function init()
    {
        if ($this->pagination === null) {
            throw new InvalidConfigException('The "pagination" property must be set.');
        }
    }

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page size buttons.
     */
    public function run()
    {
        echo $this->renderPageSizerButtons();
    }

    /**
     * Renders the page buttons.
     * @return string the rendering result
     */
    protected function renderPageSizerButtons()
    {
        if (count($this->availableSizes) === 0) {
            return '';
        }

        $buttons = [];
        $currentPageSize = $this->pagination->getPageSize();

        foreach ($this->availableSizes as $size => $label) {
            $buttons[] = $this->renderPageSizeButton($label, $size, null, $size == $currentPageSize);
        }

        return Html::tag('ul', implode(PHP_EOL, $buttons), $this->options);
    }

    /**
     * Renders a page size button.
     * You may override this method to customize the generation of page size buttons.
     * @param string $label the text label for the button
     * @param integer $pageSize the page size
     * @param string $class the CSS class for the page button.
     * @param boolean $active whether this page button is active
     * @return string the rendering result
     */
    protected function renderPageSizeButton($label, $pageSize, $class, $active)
    {
        $options = ['class' => $class === '' ? null : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageSizeCssClass);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page-size'] = $pageSize;

        return Html::tag(
            'li', 
            Html::a($label, $this->createUrl($this->pagination, $pageSize), $linkOptions), 
            $options
        );
    }
    
    /**
     * Creates an url for the specified page size.
     * @param \yii\data\Pagination $pagination
     * @param integer $pageSize page size
     * @param boolean $absolute whether to create an absolute URL. Defaults to `false`.
     * @return string the created URL
     */
    protected function createUrl($pagination, $pageSize, $absolute = false)
    {
        if (($params = $pagination->params) === null) {
            $request = Yii::$app->getRequest();
            $params = $request instanceof Request ? $request->getQueryParams() : [];
        }

        $currentPageSize = $pagination->getPageSize();
        $currentPage = $pagination->getPage();
        $target = $currentPage * $currentPageSize;
        $page = (int)($target / $pageSize);

        if ($page > 0 || $page >= 0 && $pagination->forcePageParam) {
            $params[$pagination->pageParam] = $page + 1;
        } else {
            unset($params[$pagination->pageParam]);
        }
        if ($pageSize != $pagination->defaultPageSize) {
            $params[$pagination->pageSizeParam] = $pageSize;
        } else {
            unset($params[$pagination->pageSizeParam]);
        }

        $params[0] = $pagination->route === null ? Yii::$app->controller->getRoute() : $pagination->route;
        $urlManager = $pagination->urlManager === null ? Yii::$app->getUrlManager() : $pagination->urlManager;
        if ($absolute) {
            return $urlManager->createAbsoluteUrl($params);
        }
        return $urlManager->createUrl($params);
    }
}
