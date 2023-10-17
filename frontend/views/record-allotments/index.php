<?php

use app\components\helpers\MyHelper;
use app\models\FundClusterCode;
use app\models\recordAllotmentEntriesSearch;
use app\models\RecordAllotmentsSearch;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RecordAllotmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Record Allotments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="record-allotments-index">


    <p>
        <?= Html::a('<i class="fa fa-pencil-alt"></i> Create Record Allotment', ['create'], ['class' => 'btn btn-success']) ?>
        <!-- <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button> -->
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

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
                        'action' => ['record-allotments/import'],
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
    // $x = new recordAllotmentEntriesSearch();
    // $y = $x->search(Yii::$app->request->queryParams);
    // $gridColumns = [
    //     'id',
    //     [
    //         'label' => "Reporting Period",

    //         'value' => "recordAllotment.reporting_period"
    //     ],
    //     [
    //         'label' => "Serial Number",
    //         'attribute' => "record_allotment_id",

    //         'value' => "recordAllotment.serial_number"
    //     ],
    //     [
    //         'label' => "Particular",
    //         'attribute' => 'recordAllotment.particulars'

    //     ],
    //     [
    //         'label' => 'Document Recieve',
    //         'attribute' => 'recordAllotment.documentRecieve.name'
    //     ],
    //     [
    //         'label' => 'Fund CLuster Code',
    //         'attribute' => 'recordAllotment.fundClusterCode.name',
    //         'filter' => Html::activeDropDownList(
    //             $searchModel,
    //             'fund_cluster_code_id',
    //             ArrayHelper::map(FundClusterCode::find()->asArray()->all(), 'id', 'name'),
    //             ['class' => 'form-control', 'prompt' => 'Fund Cluster Codes']
    //         )
    //     ],

    //     [
    //         'label' => 'Financing Source Code',
    //         'attribute' => 'recordAllotment.financingSourceCode.name',
    //     ],
    //     [
    //         'label' => 'Fund Category and Classification Code',
    //         'attribute' => 'recordAllotment.fundCategoryAndClassificationCode.name'
    //     ], [
    //         'label' => 'Authorization Code',
    //         'attribute' => 'recordAllotment.authorizationCode.name'
    //     ],
    //     [
    //         'label' => 'MFO/PAP Code',
    //         'attribute' => 'recordAllotment.mfoPapCode.code'
    //     ],
    //     [
    //         'label' => 'MFO/PAP Name',
    //         'attribute' => 'recordAllotment.mfoPapCode.name'
    //     ],
    //     [
    //         'label' => 'MFO/PAP Name',
    //         'attribute' => 'recordAllotment.mfoPapCode.name'
    //     ],
    //     [
    //         'label' => 'Fund Source',
    //         'attribute' => 'recordAllotment.fundSource.description'
    //     ],
    //     [
    //         "label" => "UACS",
    //         'attribute' => 'chartOfAccount.uacs'
    //     ],
    //     [
    //         "label" => "General Ledger",
    //         'attribute' => 'chartOfAccount.general_ledger',
    //     ],
    //     [
    //         "label" => "Allotment Class",
    //         'attribute' => 'chartOfAccount.majorAccount.name',
    //     ],

    //     [

    //         'label' => "Amount",
    //         'attribute' => "amount",
    //         'format' => ['decimal', 2],
    //         'hAlign' => 'right'

    //     ],

    //     [

    //         'label' => "NCA/NTA",
    //         'value' => function ($model) {
    //             $x = '';
    //             if ($model->recordAllotment->documentRecieve->name === 'GARO') {
    //                 $x = 'NCA';
    //             } else {
    //                 $x = 'NTA';
    //             }
    //             return $x;
    //         }

    //     ],
    //     [

    //         'label' => "CARP/101",
    //         'value' => function ($model) {
    //             $x = '';
    //             if ($model->recordAllotment->mfoPapCode->name === 'CARP') {
    //                 $x = 'CARP';
    //             } else {
    //                 $x = '101';
    //             }
    //             return $x;
    //         }

    //     ],
    //     [
    //         'label' => 'Total ORS',
    //         'value' => function ($model) {
    //             $query = Yii::$app->db->createCommand("SELECT 
    //             SUM(raoud_entries.amount) as total_dv
    //             FROM raouds
    //             LEFT JOIN raoud_entries ON raouds.id = raoud_entries.raoud_id
    //             LEFT JOIN process_ors ON raouds.process_ors_id = process_ors.id
    //             WHERE 
    //             raouds.process_ors_id IS NOT NULL
    //             AND process_ors.is_cancelled = 0
    //             AND raouds.record_allotment_entries_id = :allotment_id

    //             ")->bindValue(':allotment_id', $model->id)
    //                 ->queryScalar();
    //             return $query;
    //         },
    //         'format' => ['decimal', 2]
    //     ],
    //     [
    //         'label' => 'Total DV',
    //         'value' => function ($model) {
    //             $query = Yii::$app->db->createCommand("SELECT 

    //             SUM(raoud_entries.amount) as dv
    //             FROM raouds
    //             LEFT JOIN raoud_entries ON raouds.id = raoud_entries.raoud_id
    //             RIGHT  JOIN(
    //             SELECT 
    //             process_ors.id
    //             FROM process_ors
    //             LEFT JOIN dv_aucs_entries ON process_ors.id = dv_aucs_entries.process_ors_id
    //             LEFT JOIN dv_aucs ON dv_aucs_entries.dv_aucs_id = dv_aucs.id
    //             WHERE process_ors.is_cancelled = 0 
    //             AND dv_aucs.is_cancelled = 0
    //             GROUP BY process_ors.id
    //             ) as dv ON raouds.process_ors_id = dv.id
    //             WHERE raouds.record_allotment_entries_id= :allotment_id
    //             GROUP BY raouds.record_allotment_entries_id
    //             ORDER BY raouds.record_allotment_entries_id

    //             ")->bindValue(':allotment_id', $model->id)
    //                 ->queryScalar();
    //             return $query;
    //         },
    //         'format' => ['decimal', 2],
    //         'hAlign' => 'right'
    //     ],
    //     [
    //         'label' => 'Update',
    //         'format' => 'raw',
    //         'value' => function ($model) {

    //             $t = yii::$app->request->baseUrl . "/index.php?r=record-allotments/update&id=$model->record_allotment_id";
    //             return ' ' . Html::a('', $t, ['class' => 'btn-xs btn-primary fa fa-pencil-square-o']);
    //         },
    //         'hiddenFromExport' => true,
    //     ],
    //     [
    //         'class' => '\kartik\grid\ActionColumn',
    //         'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>', 'style' => "display:none"],
    //         'updateOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>', 'style' => "display:none"],
    //     ]
    // ];
    $col = [
        'budget_year',
        'reporting_period',
        'date_issued',
        'valid_until',
        'allotmentNumber',
        'office_name',
        'division',
        [
            'attribute' => 'mfo_name',
            'value' => function ($model) {
                return $model->mfo_code . '-' . $model->mfo_name;
            }
        ],
        'fund_source_name',
        'uacs',
        'account_title',
        'particulars',
        'document_recieve',
        'fund_cluster_code',
        'financing_source_code',
        'fund_classification',
        'authorization_code',
        'responsibility_center',
        'allotment_class',
        'nca_nta',
        'carp_101',
        'book',
        'allotment_type',
        'book_name',
        [
            'attribute' => 'amount',
            'label' => 'Allotment Amount',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'ttlOrsAmt',
            'label' => 'Total Ors',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'ttlPrAmt',
            'label' => 'Total In Purchase Request',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'ttlTrAmt',
            'label' => 'Total in Transaction',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'ttlAdjustment',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'balance',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'balAfterObligation',
            'label' => 'Balance After Obligation',
            'format' => ['decimal', 2]
        ],

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return MyHelper::gridDefaultAction($model->id, 'none');
            }
        ],

    ];


    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'panel' => [
            'heading' => 'List of Record Allotments',
            'type' => 'primary',

        ],
        'pjax' => true,
        'toolbar' => [
            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $col,
                    'filename' => 'RecordAllotments',
                    'exportConfig' => [
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_EXCEL => false,
                        ExportMenu::FORMAT_HTML => false,
                    ]
                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'columns' => $col,
    ]); ?>


</div>