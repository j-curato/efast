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

        [
            'label' => 'BURS Number',
            'attribute' => 'process_ors_id',
            'value' => 'processOrs.serial_number',
            // 'value' => 'processOrs.reporting_period'
        ],
        'reporting_period',
        [
            'label' => "Transaction",
            'attribute' => "processOrs.transaction.tracking_number"
        ],

        [
            'label' => 'Allotment Number',
            'attribute' => 'recordAllotmentEntries.recordAllotment.serial_number',
        ],
        [
            'label' => 'Allotment UACS Code',
            'attribute' => 'recordAllotmentEntries.chartOfAccount.uacs',
        ],
        [
            'label' => 'Allotment General Ledger',
            'attribute' => 'recordAllotmentEntries.chartOfAccount.general_ledger',
        ],

        [
            'label' => 'ORS UACS Object Code',
            'value' => 'raoudEntries.chartOfAccount.uacs',
        ],
        [
            'label' => 'General Ledger',
            'value' => 'raoudEntries.chartOfAccount.general_ledger',
        ],
        [
            'label' => 'Payee',
            'value' => 'processOrs.transaction.payee.account_name',
        ],

        [
            'label' => 'Amount',
            'attribute' => 'raoudEntries.amount',
            'format' => ['decimal', 2],
            'pageSummary' => true,
        ],
        [
            'label' => 'Adjust Amount',
            'value' => function ($model) {
                $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as total
                    FROM `raouds`,raoud_entries
                    WHERE raouds.id=raoud_entries.raoud_id
                    AND raoud_entries.amount >0
                    AND raoud_entries.parent_id_from_raoud = $model->id
                     ")->queryOne();
                if (!empty($query['total'])) {
                    return $query['total'];
                } else {
                    return '';
                }
            },
            'format' => ['decimal', 2]
        ],

        // [
        //     'label' => 'Adjust',
        //     'format' => 'raw',
        //     'value' => function ($model) {

        //         $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as total
        //             FROM `raouds`,raoud_entries
        //             WHERE raouds.id=raoud_entries.raoud_id
        //             AND raoud_entries.amount >0
        //             AND raoud_entries.parent_id_from_raoud = $model->id
        //              ")->queryOne();
        //         $amount = $model->raoudEntries->amount;
        //         if ($query['total'] < $amount  && $amount > 0) {

        //             $t = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/adjust&id=$model->id";
        //             return ' ' . Html::a('', $t, ['class' => 'btn-xs btn-primary fa fa-pencil-square-o']);
        //         } else {
        //             return "";
        //         }
        //         // return $query['total'];
        //     },
        //     'hiddenFromExport' => true,
        // ],
        [
            'label' => 'Adjust',
            'format' => 'raw',
            'value' => function ($model) {

                $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as total
                    FROM `raouds`,raoud_entries
                    WHERE raouds.id=raoud_entries.raoud_id
                    AND raoud_entries.amount >0
                    AND raoud_entries.parent_id_from_raoud = $model->id
                     ")->queryOne();
                $amount = $model->raoudEntries->amount;
                if ($query['total'] < $amount  && $amount > 0) {

                    $t = yii::$app->request->baseUrl . "/index.php?r=process-burs/re-align&id=$model->id";
                    return ' ' . Html::a('', $t, ['class' => 'btn-xs btn-success fa fa-pencil-square-o']);
                } else {
                    return "";
                }
                // return $query['total'];
            },
            'hiddenFromExport' => true
        ],
        [
            'label' => 'View',
            'format' => 'raw',
            'value' => function ($model) {
                $t = yii::$app->request->baseUrl . "/index.php?r=process-burs/view&id=$model->id";
                return ' ' . Html::a('', $t, ['class' => 'btn-xs btn-info fa fa-eye']);

                // return $query['total'];
            },
            'hiddenFromExport' => true,
        ],


        // ['class' => 'yii\grid\ActionColumn'],
        // [
        //     'class' => '\kartik\grid\ActionColumn',
        //     // 'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>', 'style' => "display:none"],
        //     // 'delete' => false

        // ]
    ];

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'heading' => '<h3 class="panel-title"> Process Ors</h3>',
            'type' => 'primary',
            // 'before' => Html::a('<i class="glyphicon glyphicon-plus"></i>Create Process Ors', ['create'], ['class' => 'btn btn-success']),

        ],
        'showPageSummary' => true,

        'toolbar' => [
            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $exportColumns,
                    'filename' => "ORS",
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