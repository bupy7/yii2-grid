yii2-grid
=========
Simple extended `yii\grid\GridView`.

**Functional:**

- Wrapping GridView in [Bootstrap3 Panel](http://getbootstrap.com/components/#panels).
- Ability changing size of page.
- Column of 'Total' with ability using custom formulas.
- Hard-header.
- Custom tags of template the GridView.
- Ability add/remove visible columns of real-time.

![Screenshot1](screenshot1.png)

![Screenshot2](screenshot2.png)

![Screenshot3](screenshot3.png)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist bupy7/yii2-grid "*"
```

or add

```
"bupy7/yii2-grid": "*"
```

to the require section of your `composer.json` file.


Usage
-----

### Simple usage

```php
use bupy7\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ['class' => 'yii\grid\CheckboxColumn'],
        'attribute1',
        'attribute2',
    ],
]);
```

### Adding delete button

Adding your view:

```php
use bupy7\grid\GridView;

$panelHeadingTemplate = <<<HTML
    <div class="col-md-6">{controls}</div>
    <div class="col-md-6 text-right">{pageSizer}</div>
    <div class="clearfix"></div>
HTML;
echo GridView::widget([
    'customTags' => [
        'controls' => $this->render('_controls'),
    ],
    'panelHeadingTemplate' => $panelHeadingTemplate,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ['class' => 'yii\grid\CheckboxColumn'],
        'attribute1',
        'attribute2',
    ],
]);
```

Adding your ```_controls``` view:

```php
<?php
use yii\helpers\Html;
use yii\helpers\Json;
?>
<?= Html::a(Yii::t('app', 'DELETE'), ['delete'], [
    'id' => 'all-delete',
    'class' => 'btn btn-danger',
    'title' => Yii::t('app', 'DELETE'),
]); ?>
<?php
$message = Json::encode(Yii::t('app', 'CONFIRM_DELETE_SELECTED_ITEMS'));
$js = <<<JS
var grid = $('#all-delete').closest('.grid-view');
$('#all-delete').on('click', function() {
    th = $(this);
    yii.confirm($message, function() {
        $.post(th.attr('href'), {ids: grid.yiiGridView('getSelectedRows')});
    });
    return false;
});
JS;
$this->registerJs($js);
```
 
Adding your controller:

```php
public function actionDelete($id = null)
{
    if ($id === null) {
        $ids = (array)Yii::$app->request->post('ids');
    } else {
        $ids = (array)$id;
    }
    for ($i = 0; $i != count($ids); $i++) {
        $this->findModel($ids[$i])->delete();
    }
    return $this->redirect(['index']);
}
```

### Adding ability change visible columns

#### Via session

Override session component:

```php
use bupy7\grid\interfaces\StorageInterface;

/**
 * @inheritdoc
 */
class Session extends \yii\web\Session implements StorageInterface
{

}
```

Adding your config of application:

```php
'components' => [
    'gridManager' => [
        'class' => 'bupy7\grid\components\Manager',
        'storage' => 'session',
    ],
]
```

Adding your controller:

```php
use bupy7\grid\actions\VisibleColumnsAction;
use yii\helpers\Url;

public function actions()
{
    return parent::actions() + [
        'visible-columns' => [
            'class' => VisibleColumnsAction::className(),
            'gridId' => 'example-grid', 
        ],
    ];
}

public function actionIndex()
{
    Url::remember();

    $searchModel = new ExampleSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $visibleColumns = Yii::$app->gridManager->getVisibleColumns('example-grid');

    return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'visibleColumns' => $visibleColumns,
    ]);
}
```

Adding your search model:

```php
public function gridColumnsList()
{
    return [
        'attribute1' => 'Label of attribute1',
        'attribute2' => 'Label of attribute2',
    ];
}
```

Adding your view:

```php
use bupy7\grid\GridView;

$panelHeadingTemplate = <<<HTML
    <div class="col-md-6">{controls}</div>
    <div class="col-md-6 text-right">{pageSizer}</div>
    <div class="clearfix"></div>
HTML;
echo GridView::widget([
    'customTags' => [
        'controls' => $this->render('_controls', [
            'visibleColumns' => $visibleColumns,
            'searchModel' => $searchModel,
        ]),
    ],
    'panelHeadingTemplate' => $panelHeadingTemplate,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'visibleColumns' => $visibleColumns,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'attribute1',
        'attribute2',
    ],
]);
```

Adding your `_controls` view:

```php
use bupy7\grid\widgets\VisibleColumnsWidget;

<?= VisibleColumnsWidget::widget([
    'gridId' => 'example-grid',
    'modalOptions' => [
        'header' => Yii::t('app', 'SELECT_COLUMNS'),
        'toggleButton' => [
            'label' => Yii::t('app', 'SELECT_COLUMNS'),
            'class' => 'btn btn-default',
        ],
    ],
    'actionForm' => ['visible-columns'],
    'submitBtnLabel' => Yii::t('app', 'APPLY'),
    'columnsList' => $searchModel->gridColumnsList(),
]); ?>
```

### Adding ability display all rows of grid

```php
echo GridView::widget([

    ...

    'pageSizer' => [
        'availableSizes' => [20 => '20', 50 => '50', 100 => '100', -1 => Yii::t('app', 'ALL_PAGES')],
    ],

    ...

]);
```

More information to `bupy7\grid\LinkPageSizer`.

### Adding total column of grid

Added sum total:

```php
[
    'class' => 'bupy7\grid\TotalColumn',
    'format' => 'currency',
    'attribute' => 'total_cost',
]
```

More information to `bupy7\grid\TotalColumn`.

--------------------------------------------------------------------------------

More information about `GridView` to `bupy7\grid\GridView`.

##License

yii2-grid is released under the BSD 3-Clause License.
