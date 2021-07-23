<?php

use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashDisbursementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Disbursements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-disbursement-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cash Disbursement', ['create'], ['class' => 'btn btn-success']) ?>
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
                    <center><a href="/afms/frontend/web/import_formats/Cash_Disbursement and DV Format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <?php


                    $form = ActiveForm::begin([
                        'action' => ['cash-disbursement/import'],
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
    $gridColumn = [
        // ['class' => 'yii\grid\SerialColumn'],

        'id',
        // 'book_id',
        [
            "label" => "Book",
            "attribute" => "book_id",
            "value" => "book.name"
        ],
        'reporting_period',
        'mode_of_payment',
        'check_or_ada_no',
        'ada_number',
        'issuance_date',
        [
            'label' => "DV Number",
            "attribute" => "dv_aucs_id",
            'value' => 'dvAucs.dv_number'
        ],
        [
            'label' => "Payee",
            "attribute" => "dvAucs.payee.account_name"
        ],
        [
            'label' => "Particular",
            "attribute" => "dvAucs.particular"
        ],
        [
            'label' => "Amount Disbursed",
            'format' => ['decimal', 2],
            'value' => function ($model) {
                $query = (new \yii\db\Query())
                    ->select(["SUM(dv_aucs_entries.amount_disbursed) as total_disbursed"])
                    ->from('dv_aucs')
                    ->join("LEFT JOIN", "dv_aucs_entries", "dv_aucs.id = dv_aucs_entries.dv_aucs_id")
                    ->where("dv_aucs.id =:id", ['id' => $model->dv_aucs_id])
                    ->one();

                return $query['total_disbursed'];
            }
        ],
        [
            'label' => 'Good/Cancelled',
            'attribute'=>'is_cancelled',
            'value' => function ($model) {
                $model->is_cancelled ? $q = 'cancelled' : $q = 'Good';
                return $q;
            }
        ],

        ['class' => 'yii\grid\ActionColumn'],
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
        ],
        'toolbar' =>  [
            [
                'content' =>
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumn,
                    'filename' => 'Cash Disbursements',
                    'exportConfig' => [
                        ExportMenu::FORMAT_TEXT => false,
                        // ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_EXCEL => false,
                        ExportMenu::FORMAT_HTML => false,
                    ]

                ]),
                'options' => ['class' => 'btn-group mr-2', 'style' => 'margin-right:20px']
            ],

        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        'columns' => $gridColumn
    ]); ?>

    <style>
        .grid-view td {
            white-space: normal;
            width: 10rem;
            padding: 0;
        }
    </style>
</div>