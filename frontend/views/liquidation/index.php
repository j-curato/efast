<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LiquidataionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Liquidations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liquidation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Liquidation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <!-- LIQUIDATION ENTRIES AND MODEL NAA SA INDEX CONTROLLER GE CHANGE -->


    <?php

    $gridColumn = [
        'id',
        [
            'label' => 'Check Date',
            'value' => 'liquidation.check_date'
        ],
        [
            'label' => 'Check Number',
            'value' => 'liquidation.check_number'
        ],
        [
            'label' => 'Fund Source',
            'attribute' => 'advances.particular'
        ],
        [
            'label' => 'Particular',
            'attribute' => 'liquidation.particular'
        ],

        [
            'label' => 'Payee',
            'value' => 'liquidation.payee.account_name'
        ],
        [
            'label' => 'Object Code',
            'value' => 'chartOfAccount.uacs'
        ],
        [
            'label' => 'General Ledger',
            'value' => 'chartOfAccount.general_ledger'
        ],
        [
            'label' => 'Withrawals',
            'attribute' => 'withdrawals',
            'hAlign' => 'right'

        ],
        [
            'label' => 'Vat/Non-vat',
            'attribute' => 'vat_nonvat',
            'hAlign' => 'right'

        ],
        [
            'label' => 'EWT',
            'attribute' => 'ewt_goods_services',
            'hAlign' => 'right'

        ],
        [
            'label' => 'Gross Payment',
            'value' => function ($model) {
                return $model->withdrawals + $model->vat_nonvat + $model->ewt_goods_services;
            },
            'hAlign' => 'right'
        ],
        [
            'label' => 'View',
            'format' => 'raw',
            'value' => function ($model) {

                return Html::a("", ['view', 'id' => $model->liquidation_id], ['class' => 'btn-xs btn-primary fa fa-eye']);
                // return $query['total'];
            },
            'hiddenFromExport' => true,
            'vAlign' => 'middle',
        ],
        // [
        //     'class' => '\kartik\grid\ActionColumn',
        //     'updateOptions' => [
        //         'update' => false
        //     ]

        // ],
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
    }
</style>