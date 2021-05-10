<?php

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

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i>Create Record Allotment', ['create'], ['class' => 'btn btn-success']) ?>
        <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button>
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
    $x = new recordAllotmentEntriesSearch();
    $y = $x->search(Yii::$app->request->queryParams);
    $gridColumns = [
        'id',
        [
            'label' => "Serial Number",
            'attribute' => "record_allotment_id",

            'value' => "recordAllotment.serial_number"
        ],
        [
            'label' => "Particular",
            'attribute' => 'recordAllotment.particulars'

        ],
        [
            'label' => 'Document Recieve',
            'attribute' => 'recordAllotment.documentRecieve.name'
        ],
        [
            'label' => 'Fund CLuster Code',
            'attribute' => 'recordAllotment.fundClusterCode.name',
            'filter' => Html::activeDropDownList(
                $searchModel,
                'fund_cluster_code_id',
                ArrayHelper::map(FundClusterCode::find()->asArray()->all(), 'id', 'name'),
                ['class' => 'form-control', 'prompt' => 'Fund Cluster Codes']
            )
        ],

        [
            'label' => 'Financing Source Code',
            'attribute' => 'recordAllotment.financingSourceCode.name',
        ],
        [
            'label' => 'Fund Category and Classification Code',
            'attribute' => 'recordAllotment.fundCategoryAndClassificationCode.name'
        ], [
            'label' => 'Authorization Code',
            'attribute' => 'recordAllotment.authorizationCode.name'
        ],
        [
            'label' => 'MFO/PAP Code',
            'attribute' => 'recordAllotment.mfoPapCode.code'
        ],
        [
            'label' => 'MFO/PAP Name',
            'attribute' => 'recordAllotment.mfoPapCode.name'
        ],
        [
            'label' => 'MFO/PAP Name',
            'attribute' => 'recordAllotment.mfoPapCode.name'
        ],
        [
            'label' => 'Fund Source',
            'attribute' => 'recordAllotment.fundSource.description'
        ],
        [
            "label" => "UACS",
            'attribute' => 'chartOfAccount.uacs'
        ],
        [
            "label" => "General Ledger",
            'attribute' => 'chartOfAccount.general_ledger',
        ],

        [

            'label' => "Amount",
            'attribute' => "amount",
            'format' => ['decimal', 2],
            'hAlign'=>'right'

        ],
        [
            'label' => 'Update',
            'format' => 'raw',
            'value' => function ($model) {



                $t = yii::$app->request->baseUrl . "/index.php?r=record-allotments/update&id=$model->record_allotment_id";
                return ' ' . Html::a('', $t, ['class' => 'btn-xs btn-primary fa fa-pencil-square-o']);
            },
            'hiddenFromExport' => true,
        ],
        [
            'class' => '\kartik\grid\ActionColumn',
            'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>', 'style' => "display:none"],
            'updateOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>', 'style' => "display:none"],
        ]
    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $y,
        'filterModel' => $x,
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'panel' => [
            'heading' => '<h3 class="panel-title"> Record Allotments</h3>',
            'type' => 'primary',

        ],
        'toolbar' => [
            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $y,
                    'columns' => $gridColumns,
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
        'columns' => $gridColumns,
    ]); ?>


</div>