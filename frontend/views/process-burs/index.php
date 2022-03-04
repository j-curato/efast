<?php

use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\processOrsEntriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Process Burs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-entries-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i>Create Process Ors', ['create'], ['class' => 'btn btn-success']) ?>
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
                    <center><a href="sub_account1/sub_account1_format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <label for="ledger"> SELECT GENERAL LEDGER</label>
                    <?php
                    $ledger = Yii::$app->db->createCommand("SELECT chart_of_accounts.id, CONCAT(chart_of_accounts.uacs,' - ',chart_of_accounts.general_ledger) as name FROM chart_of_accounts")->queryAll();
                    ?>
                    <?php

                    $form = ActiveForm::begin([
                        'action' => ['process-ors-entries/import'],
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

    <!-- RAOUDS ANG MODEL ANI. TRIP KO LANG -->
    <!-- NAA SA PROCESS ORS ENTRIES CONTROLLER SA INDEX NAKO GE CHANGE -->

    <?php
    $exportColumns = [
        'id',

        'reporting_period',
        [
            'label' => 'Date',
            'value' => "processOrs.date"
        ],
        [
            'label' => 'Transaction Number',
            'value' => "processOrs.transaction.tracking_number"
        ],
        [
            'label' => 'Obligation Number',
            'value' => "processOrs.serial_number"
        ],
        [
            'label' => 'Allotment Number',
            'value' => "recordAllotmentEntries.recordAllotment.serial_number"
        ],
        [
            'label' => 'Allotment UACS Object Code',
            'value' => "recordAllotmentEntries.chartOfAccount.uacs"
        ],
        [
            'label' => 'Obligation UACS Object Code',
            'value' => "raoudEntries.chartOfAccount.uacs"
        ],
        [
            'label' => 'Obligation Account Title',
            'value' => "raoudEntries.chartOfAccount.general_ledger"
        ],
        [
            'label' => 'Payee',
            'value' => "processOrs.transaction.payee.account_name"
        ],
        [
            'label' => 'Particular',
            'value' => "processOrs.transaction.particular"
        ],
        [
            'label' => 'Obligation Incured',
            'attribute' => 'raoudEntries.amount',
            'format' => ['decimal', 2],
            'pageSummary' => true,
        ],
        [
            'label' => 'Good/Cancelled',
            'value' => function ($model) {
                if ($model->processOrs->is_cancelled) {
                    return 'Cancelled';
                } else {
                    return 'Good';
                }
            },
        ],
    ];
    $gridColumns = [
        'id',
        'serial_number',
        'tracking_number',
        'payee',
        'particular',
        'allotment_uacs',
        'allotment_account_title',
        'ors_uacs',
        'ors_account_title',
        'amount',
        'is_cancelled',
        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                $adjust = yii::$app->request->baseUrl . "/index.php?r=process-burs/re-align&id=$model->id";
                $view = yii::$app->request->baseUrl . "/index.php?r=process-burs/view&id=$model->id";
                return ' ' . Html::a('', $adjust, ['class' => 'btn-xs btn-success fa fa-pencil-square-o'])
                    . ' ' . Html::a('', $view, ['class' => 'btn-xs btn-primary fa fa-eye']);
            },
            'hiddenFromExport' => true
        ],

    ];
    $exportColumns = [

        'id',
        'reporting_period',
        'date',
        'tracking_number',

        [
            'label' => 'Obligation Number',
            'value' => "serial_number"
        ],
        'ors_uacs',
        'ors_account_title',
        'ors_book',
        [
            'label' => 'Allotment Number',
            'value' => "allotment_serial_number"
        ],
        'allotment_book',
        'allotment_uacs',
        'allotment_account_title',
        'mfo_name',
        'document_name',


        "payee",
        'particular',
        [
            'label' => 'Obligation Incured',
            'attribute' => 'amount',
            'format' => ['decimal', 2],
            'pageSummary' => true,
        ],
        [

            'label' => "NCA/NTA",
            'value' => function ($model) {
                $x = '';
                if ($model->document_name === 'GARO') {
                    $x = 'NCA';
                } else {
                    $x = 'NTA';
                }
                return $x;
            }

        ],
        [

            'label' => "CARP/101",
            'value' => function ($model) {
                $x = '';
                if ($model->mfo_name === 'CARP') {
                    $x = 'CARP';
                } else {
                    $x = '101';
                }
                return $x;
            }

        ],
        'is_cancelled',

        [
            'label' => 'Total Amount Disbursed',
            'value' => function ($model) {
                $query = Yii::$app->db->createCommand("SELECT SUM(dv_aucs_entries.amount_disbursed) as total
                    FROM dv_aucs_entries
                    WHERE  dv_aucs_entries.process_ors_id = $model->id
                     ")->queryScalar();
                return $query;
            },
            'format' => ['decimal', 2]
        ],
    ];

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'heading' => '<h3 class="panel-title"> Process BURS</h3>',
            'type' => 'primary',
            // 'before' => Html::a('<i class="glyphicon glyphicon-plus"></i>Create Process Ors', ['create'], ['class' => 'btn btn-success']),

        ],
        'showPageSummary' => true,

        'toolbar' => [
            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $exportColumns,
                    'filename' => "BURS",
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
        'export' => false,


        'columns' => $gridColumns
    ]); ?>

    <style>
        .grid-view td {
            white-space: normal;
            width: 2rem;
        }
    </style>

</div>