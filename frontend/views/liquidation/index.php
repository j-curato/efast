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
        'reporting_period',
        'dv_number',
        'check_date',
        'check_number',
        'fund_source',
        [
            'label'=>'Particular',
            'value'=>function($model){
                if (!empty($model->particular)){
                    $particular = $model->particular;
                }
                else{
                    $particular = $model->transaction_particular;
                }
                return $particular ;
            }
        ],
        [
            'label'=>'Payee',
            'value'=>function($model){
                if (!empty($model->payee)){
                    $payee = $model->payee;
                }
                else{
                    $payee = $model->transaction_payee;
                }
                return $payee;
            }
        ],
        'object_code',
        'account_title',
        'withdrawals',
        'vat_nonvat',
        'expanded_tax',
        'liquidation_damage',
        'gross_payment',
        'province',

    ];
    $province = \Yii::$app->user->identity->province;
    $viewSearchModel = new LiquidationViewSearch();
    // if (
    //     $province === 'adn' ||
    //     $province === 'sdn' ||
    //     $province === 'sds' ||
    //     $province === 'sdn' ||
    //     $province === 'pdi'

    // ) {
    //     $viewSearchModel->province = \Yii::$app->user->identity->province;
    //     // echo \Yii::$app->user->identity->province;
    // }

    $viewDataProvider = $viewSearchModel->search(Yii::$app->request->queryParams);

    $viewDataProvider->pagination = ['pageSize' => 10];
    // echo \Yii::$app->user->identity->province;
    $viewColumn = [
        'province',
        // [
        //     'attribute' => 'province',
        //     'value' => function ($model) {

    
        //         return strtoupper($model->province);
        //     }
        // ],
        'check_date',
        'check_number',
        'dv_number',
        'reporting_period',
        // 'particular',
        [
            'label' => 'Payee',
            'attribute' => 'payee',
            'value' => function ($model) {

                if (!empty($model->tr_payee)) {
                    $payee = $model->tr_payee;
                } else {
                    $payee = $model->payee;
                }
                return $payee;
            }
        ],
        [
            'label' => 'Particular',
            'attribute' => 'particular',
            'value' => function ($model) {

                if (!empty($model->tr_particular)) {
                    $particular = $model->tr_particular;
                } else {
                    $particular = $model->particular;
                }
                return $particular;
            }
        ],

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
            'label' => 'Total Liquidation Damage',
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