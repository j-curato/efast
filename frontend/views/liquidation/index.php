<?php

use app\models\LiquidationViewSearch;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
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

    <?php if (Yii::$app->user->can('create_liquidation')) { ?>
        <p>
            <?= Html::a('Create Liquidation', ['create'], ['class' => 'btn btn-success']) ?>
            <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button>
        </p>

        <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">UPLOAD Cash Disbursement</h4>
                    </div>
                    <div class='modal-body'>
                        <center><a href="import_formats/Cash_Disbursement and DV Format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                        <hr>
                        <?php


                        $form = ActiveForm::begin([
                            'action' => ['liquidation/import'],
                            'method' => 'post',
                            'id' => 'formupload',
                            'options' => [
                                'enctype' => 'multipart/form-data',
                            ], // important
                        ]);
                        // echo '<input type="file">';
                        echo FileInput::widget([
                            'name' => 'file',
                            // 'options' => ['multiple' => true],
                            'id' => 'fileupload',
                            'pluginOptions' => [
                                'showPreview' => true,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => true,
                            ]
                        ]);

                        ActiveForm::end();


                        ?>

                    </div>
                </div>
            </div>
        </div>

    <?php }
    ?>

    <!-- LIQUIDATION ENTRIES AND MODEL NAA SA INDEX CONTROLLER GE CHANGE -->


    <?php

    $gridColumn = [
        'id',
        [
            'label' => 'DV Number',
            'attribute' => 'liquidation.dv_number'

        ],
        [
            'label' => 'Check Date',
            'value' => 'liquidation.check_date',
        ],
        [
            'label' => 'Check Number',
            'value' => 'liquidation.check_number'
        ],
        [
            'label' => 'Fund Source',
            'attribute' => 'advancesEntries.fund_source'
        ],
        [
            'label' => 'Particular',
            'attribute' => 'liquidation.poTransaction.particular'
        ],

        [
            'label' => 'Payee',
            'value' => 'liquidation.poTransaction.payee'
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
            'attribute' => 'expanded_tax',
            'hAlign' => 'right'

        ],
        [
            'label' => 'Gross Payment',
            'value' => function ($model) {
                return $model->withdrawals + $model->vat_nonvat + $model->expanded_tax;
            },
            'hAlign' => 'right'
        ],
        [
            'label' => 'View',
            'format' => 'raw',
            'value' => function ($model) {

                return Html::a("", ['view', 'id' => $model->liquidation_id], ['class' => 'btn-xs  fa fa-eye']);
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
    $viewSearchModel = new LiquidationViewSearch();
    if (!empty(\Yii::$app->user->identity->province)) {
        $viewSearchModel->province = \Yii::$app->user->identity->province;
        // echo \Yii::$app->user->identity->province;
    }

    $viewDataProvider = $viewSearchModel->search(Yii::$app->request->queryParams);

    $viewDataProvider->pagination = ['pageSize' => 10];
    // echo \Yii::$app->user->identity->province;
    $viewColumn = [
        'province',

        'check_date',
        'check_number',
        'dv_number',
        'reporting_period',
        'payee',
        'particular',

        [
            'label' => 'Total Disbursements',
            'attribute' => 'total_withdrawal',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Total Sales Tax (VAT/Non-VAT)',
            'attribute' => 'total_vat',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Income Tax (Expanded Tax)',
            'attribute' => 'total_expanded',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Total Liquidation',
            'attribute' => 'total_liquidation_damage',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Gross Payment',
            'attribute' => 'gross_payment',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],

        [
            'label' => 'View',
            'format' => 'raw',
            'value' => function ($model) {

                return Html::a("", ['view', 'id' => $model->id], ['class' => 'btn-xs  fa fa-eye']);
                // return $query['total'];
            },
            'hiddenFromExport' => true,
            'vAlign' => 'middle',
        ],
    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $viewDataProvider,
        'filterModel' => $viewSearchModel,
        'columns' => $viewColumn,
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