<?php

namespace bupy7\grid;

use yii\helpers\ArrayHelper;

class GridView extends \yii\grid\GridView
{
    /**
     * @var array the configuration for the page sizer widget. By default, [[LinkPageSizer]] will be
     * used to render the page sizer. You can use a different widget class by configuring the "class" element.
     */
    public $pageSizer = [];
    /**
     * @var string the layout that determines how different sections of the list view should be organized.
     * The following tokens will be replaced with the corresponding section contents:
     *
     * - `{summary}`: the summary section. See [[renderSummary()]].
     * - `{errors}`: the filter model error summary. See [[renderErrors()]].
     * - `{items}`: the list items. See [[renderItems()]].
     * - `{sorter}`: the sorter. See [[renderSorter()]].
     * - `{pager}`: the pager. See [[renderPager()]].
     * - `{pagesizer}`: the page sizer. See [[renderPagesizer()]].
     */
    public $layout = "{summary}\n{items}\n{pager}\n{pagesizer}";

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case "{pagesizer}":
                return $this->renderPageSize();
            default:
                return parent::renderSection($name);
        }
    }

    /**
     * Renders the page sizer.
     * @return string the rendering result
     */
    public function renderPageSize()
    {
        $pagination = $this->dataProvider->getPagination();
        if ($pagination === false || $this->dataProvider->getCount() <= 0) {
            return '';
        }
        /* @var $class LinkPageSizer */
        $pageSizer = $this->pageSizer;
        $class = ArrayHelper::remove($pageSizer, 'class', LinkPageSize::className());
        $pageSizer['pagination'] = $pagination;
        $pageSizer['view'] = $this->getView();

        return $class::widget($pageSizer);
    }
}
