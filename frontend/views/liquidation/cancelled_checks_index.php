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

$this->title = 'Cancelled Checks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liquidation-index">

    <p>

        <?php if (Yii::$app->user->can('liquidation')) {
            echo Html::a('<i class="fa fa-plus"></i> Create ', ['create-cancelled'], ['class' => 'btn btn-success mdModal']);
        }
        ?>
    </p>

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
                return Html::a('<i class="fa fa-pencil-alt"></i>', ['update-cancelled', 'id' => $model->id], ['class' => 'mdModal']);
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
            'heading' => 'Cancelled Checks List',
        ],
        'pjax' => true,
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
