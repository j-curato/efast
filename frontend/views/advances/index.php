<?php

use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdvancesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Advances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advances-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Advances', ['create'], ['class' => 'btn btn-success']) ?>
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
                        'action' => ['advances/import'],
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

    <?php

    // ADVANCE ACCOUNTING ENTRIES AND MODEL NAA SA CONTROLLER GE CHANGE
    $gridColumn = [

        'id',
        // 'advances.nft_number',
        [
            'label' => 'NFT Number',
            'attribute' => 'advances.nft_number',
        ],
        [
            'label' => 'Reporting Period',
            // 'attribute' => '',
            'value' => 'advances.reporting_period'
        ],
        [
            "label" => "DV Number",
            "attribute" => "att3",
            "value" => "cashDisbursement.dvAucs.dv_number"
        ],
        [
            "label" => "Mode of Payment",
            "attribute" => "cashDisbursement.mode_of_payment"
        ],
        [
            "label" => "Check Number",
            "attribute" => "cashDisbursement.check_or_ada_no"
        ],
        // [
        //     "label" => "ADA Number",
        //     "attribute" => "cashDisbursement.ada_number"
        // ],
        [
            "label" => "Check Date",
            "attribute" => "cashDisbursement.issuance_date"
        ],
        [
            "label" => "Payee",
            "attribute" => "cashDisbursement.dvAucs.payee.account_name"
        ],
        [
            "label" => "Particular",
            "attribute" => "cashDisbursement.dvAucs.particular"
        ],
        [
            "label" => "Amount",
            "attribute" => "amount",
            'hAlign' => 'right'
        ],
        [
            "label" => "Book",
            "attribute" => "cashDisbursement.book.name"
        ],
        [
            "label" => "Report",
            "attribute" => "advances.report_type"
        ],

        [
            "label" => "Province",
            "attribute" => "advances.province"
        ],
        [
            "label" => "Fund Source",
            "attribute" => "fund_source"
        ],
        [
            "label" => "Object Code",
            "attribute" => "subAccountView.object_code"
        ],
        [
            "label" => "Account Title",
            "attribute" => "subAccountView.account_title"
        ],
        [
            'label' => 'action',
            'format' => 'raw',
            'value' => function ($model) {

                $t = yii::$app->request->baseUrl . "/index.php?r=advances/update&id=$model->advances_id";
                $r = yii::$app->request->baseUrl . "/index.php?r=advances/view&id=$model->advances_id";
                return ' ' . Html::a('', $r, ['class' => 'btn-xs fa fa-eye']) . ' '
                    . Html::a('', $t, ['class' => 'btn-xs fa fa-pencil']);
            }
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
<!-- 
    <style>
        .grid-view td {
            white-space: normal;
            width: 10rem;
            padding: 0;
        }
    </style> -->


</div>