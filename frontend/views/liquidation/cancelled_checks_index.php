<?php

use app\models\LiquidationViewSearch;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LiquidataionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Liquidations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liquidation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('create_liquidation')) { ?>
        <p>
            <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', [
                'value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=liquidation/cancelled-form'),
                'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector'
            ]); ?>
        </p>

    <?php }
    ?>



    <?php


    $gridColumn = [
        'province',
        'reporting_period',
        'check_date',
        'check_number',
        'from',
        'to',
        'payee',
        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                $t = yii::$app->request->baseUrl . "/index.php?r=liquidation/cancelled-check-update&id=$model->id";
                return ' ' . Html::a('', $t, ['class' => 'btn-xs btn-primary fa fa-pencil update_form']);

                // return $query['total'];
            },
            'hiddenFromExport' => true,
        ],

    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Liquidations',
        ],
        'toolbar' => [
            [
                'content' =>
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns'  => $gridColumn,
                    'filename' => 'Liquidations',
                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_HTML => false,

                    ]
                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ]
    ]); ?>


</div>

<style>
    .grid-view td {
        white-space: normal;
        font-size: 12px;
    }
</style>

<?php
$script = <<<JS
            $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('.update_form').click(function(e){
            e.preventDefault();
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });

JS;
$this->registerJs($script);
?>