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

        // 'advances.nft_number',

        'nft_number',
        'r_center_name',
        'province',
        'fund_source',
        [
            'label'=>'Amount',
            'attribute'=>'amount',
            'hAlign'=>'right',
            'format'=>['decimal',2]
        ],
        [
            'label'=>'Total Liquidation',
            'attribute'=>'total_liquidation',
            'hAlign'=>'right',
            'format'=>['decimal',2]
        ],
        [
            'label'=>'Balance',
            'hAlign'=>'right',
            'value'=>function($model){
                return $model->amount - $model->total_liquidation;
            },
            'format'=>['decimal',2]
        ],
        'dv_number',
        'payee',
        'particular',
        'reporting_period',
        'mode_of_payment',
        'check_number',
        'check_date',
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