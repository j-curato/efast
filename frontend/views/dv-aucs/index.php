<?php

use app\models\DvAucsEntriesSearch;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
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
                    <center><a href="import_formats/DV_Format.xlsx">Download Template Here to avoid error during Upload.</a></center>
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

                    $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as total_obligated
                from process_ors,raouds,raoud_entries
                WHERE process_ors.id = raouds.process_ors_id
                AND raouds.id  = raoud_entries.raoud_id
                AND process_ors.id = :process_ors_id")
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
            'label' => "Allotment Class",
            'value' => function ($model) {
                $x = '';
                if (!empty($model->processOrs->id)) {
                    $x =  $model->processOrs->id;
                    $q = (new yii\db\Query())
                        ->select('record_allotment_id')
                        ->from('raouds')
                        ->where('record_allotment_id =:record_allotment_id', ['record_allotment_id' => $model->processOrs->id])
                        ->andWhere('is_parent =:is_parent', ['is_parent' => 1])
                        ->one();
                }
                return $x;
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
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'process_ors_id',
            // 'raoud_id',
            'dv_number',
            'reporting_period',
            'particular',

            // foreach($model->dvAucsEntries as $val){

            // },
            [
                'label' => "Payee",
                'value' => "payee.account_name"
            ],
            [
                'label' => "MRD Classification",
                'value' => "mrdClassification.name"
            ],
            [
                'label' => "Nature of Transaction",
                'value' => "natureOfTransaction.name"
            ],
            [
                'label' => "Amount Disbursed",
                'value' => function ($model) {
                    $query = (new \yii\db\Query())
                        ->select("SUM(amount_disbursed) as total_disbursed")
                        ->from("dv_aucs_entries")
                        ->where('dv_aucs_entries.dv_aucs_id = :dv_aucs_id', ['dv_aucs_id' => $model->id])
                        ->one();
                    return $query['total_disbursed'];
                },
                'format' => ['decimal', 2],
                'hAlign' => 'right',
            ],

            //'other_trust_liability_withheld',
            //'net_amount_paid',

            [
                'class' => '\kartik\grid\ActionColumn',
                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>', 'style' => "display:none"],
            ],
        ],
    ]); ?>


    <style>
        .grid-view td {
            white-space: normal;
            width: 5rem;
            padding: 0;
        }
    </style>
</div>