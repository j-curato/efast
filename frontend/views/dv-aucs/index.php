<?php

use app\components\helpers\MyHelper;
use app\models\DvAucsEntriesSearch;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DvAucsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dv Aucs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dv-aucs-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Dv Aucs', ['create'], ['class' => 'btn btn-success']) ?>
        <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button>
    </p>

    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD WFP</h4>
                </div>
                <div class='modal-body'>
                    <center><a href="/afms/frontend/web/import_formats/DV_Format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <label for="ledger"> SELECT GENERAL LEDGER</label>
                    <?php
                    $ledger = Yii::$app->db->createCommand("SELECT chart_of_accounts.id, CONCAT(chart_of_accounts.uacs,' - ',chart_of_accounts.general_ledger) as name FROM chart_of_accounts")->queryAll();
                    ?>
                    <?php

                    $form = ActiveForm::begin([
                        'action' => ['dv-aucs/import'],
                        'method' => 'POST',
                        'id' => 'import',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ], // important
                    ]);

                    // echo '<input type="file">';
                    echo "<br>";
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
    $exportSearchModel = new DvAucsEntriesSearch();
    $exportDataProvider = $exportSearchModel->search(Yii::$app->request->queryParams);
    $exportColumns = [

        [
            'label' => "Check Number",
            'value' => "dvAucs.cashDisbursement.check_or_ada_no"
        ],
        [
            'label' => "ADA Number",
            'value' => "dvAucs.cashDisbursement.ada_number"
        ],
        [
            'label' => "Cash Disbursed",
            'value' => function ($model) {
                $query  = Yii::$app->db->createCommand("SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed 
                FROM dv_aucs_entries WHERE dv_aucs_id = :dv_id
                AND dv_aucs_entries.is_deleted = 0
                ")
                    ->bindValue(':dv_id', $model->dvAucs->id)
                    ->queryScalar();
                return $query;
            }
        ],

        [
            'label' => "DV Number",
            'value' => "dvAucs.dv_number"
        ],
        [
            'label' => "Reporting Period",
            'value' => "dvAucs.reporting_period"
        ],
        [
            'label' => "ORS Number",
            'value' => "processOrs.serial_number"
        ],
        [
            'label' => "ORS Amount",
            'format' => ['decimal', 2],
            'value' => function ($model) {

                $query = '';
                $obligate = 0;
                if (!empty($model->process_ors_id)) {

                    $query = Yii::$app->db->createCommand("SELECT 
                    SUM(process_ors_entries.amount) as total_obligated
                    FROM process_ors_entries
                    WHERE 
                    process_ors_entries.process_ors_id =:process_ors_id ")
                        ->bindValue(':process_ors_id', $model->process_ors_id)
                        ->queryOne();
                    $obligate = $query['total_obligated'];
                }

                return $obligate;
            }
        ],

        [
            'label' => "Payee",
            'value' => "dvAucs.payee.account_name"
        ],
        [
            'label' => "Particular",
            'value' => "dvAucs.particular"
        ],
        [
            'label' => "DV Amount",
            'value' => "amount_disbursed"
        ],
        [
            'label' => "2306 (VAT / Non-Vat)",
            'value' => "vat_nonvat"
        ],
        [
            'label' => "2307 (EWT Goods / Services)",
            'value' => "ewt_goods_services"
        ],
        [
            'label' => "1601C (Compensation)",
            'value' => "compensation"
        ],
        [
            'label' => "Other Trust Liabilities",
            'value' => "other_trust_liabilities"
        ],
        [
            'label' => "Nature of Transaction",
            'value' => "dvAucs.natureOfTransaction.name"
        ],
        [
            'label' => "MRD Classification",
            'value' => "dvAucs.mrdClassification.name"
        ],
        [
            'label' => "Good/Cancelled",
            'value' => function ($model) {
                if ($model->dvAucs->is_cancelled) {
                    return "Cancelled";
                } else {
                    return "Good";
                }
            }
        ],
        [
            'label' => "Payable",
            'value' => function ($model) {

                return $model->dvAucs->is_payable === 1 ? 'Payable' : 'Not Payable';
            }
        ],

        'dvAucs.created_at',

        [
            'label' => 'DV Book',
            'value' =>  'dvAucs.books.name',
        ],
        [
            'label' => 'Cash Disbursement Book',
            'value' =>  'dvAucs.cashDisbursement.books.name',
        ]


    ];


    $cols =  [
        'dv_number',
        'reporting_period',
        'particular',
        'natureOfTxn',
        'mrdName',
        'payee',
        'ttlAmtDisbursed',
        'ttlTax',
        'grossAmt',
        'orsNums',
        'txnType',

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return MyHelper::gridDefaultAction($model->id);
            }
        ],
    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of DV',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'toolbar' => [


            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $exportDataProvider,
                    'columns' => $exportColumns,
                    'filename' => "DV",
                    'batchSize' => 10,
                    'stream' => false,
                    'target' => '_popup',

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
        'export' => false,
        'columns' => $cols
    ]); ?>


    <style>
        .grid-view td {
            white-space: normal;
            width: 5rem;
            padding: 0;
        }
    </style>
</div>