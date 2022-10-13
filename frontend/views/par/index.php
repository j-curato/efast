<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PAR';
$this->params['breadcrumbs'][] = $this->title;
$columns = [

    'par_number',
    'property_number',
    'unit_of_measure',
    'article',
    'description',
    'province',
    'acquisition_amount',
    'date',
    'location',
    'accountable_officer',
    'actual_user',
    'issued_by',
    'remarks',
    [
        'label' => 'Action',
        'format' => 'raw',
        'hiddenFromExport' => true,
        'value' => function ($model) {
            $btns = Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id], []);
            $btns .= ' ' . Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], ['title' => 'Update']);
            return  $btns;
        }
    ],

];
?>
<div class="par-index">


    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=par/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'PAR'
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'toolbar' => [
            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $columns,
                    'filename' => "DV",
                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_EXCEL => false,

                    ]

                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'pjax' => true,
        'columns' => $columns
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        max-width: 250rem;
        padding: 0;
    }
</style>
<?php
$script = <<<JS
            var i=false;

        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });
        
JS;
$this->registerJs($script);
?>